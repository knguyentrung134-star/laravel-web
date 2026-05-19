<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\ThanhToan;
use App\Models\DonTrongGioHang;
use App\Models\GioHang;
use App\Models\ChuongTrinhGiamGia;
use App\Models\HangTonKho;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function index(Request $request)
{
    $selectedIds = session('checkout_items', []);

    if (empty($selectedIds)) {
        return redirect()->route('cart.index')
            ->with('error', 'Không tìm thấy sản phẩm được chọn!');
    }

    $cart = $this->getOrCreateCart();

    $items = DonTrongGioHang::where('idGioHang', $cart->idGioHang)
        ->whereIn('idDonTrongGioHang', $selectedIds)   // ← Quan trọng
        ->with('sanPham')
        ->get();

    if ($items->isEmpty()) {
        session()->forget('checkout_items');
        return redirect()->route('cart.index')
            ->with('error', 'Sản phẩm đã chọn không tồn tại!');
    }

    [$total, $discount] = $this->calculateCart($items);

    $khachHang = Auth::user()->khachHang;

    return view('checkout.index', compact('items', 'total', 'discount', 'khachHang'));
}

    public function store(Request $request)
    {
        $request->validate([
            'phuongThuc'   => 'required|in:ChuyenKhoan,TienMat,TheTinDung,ViDienTu',
            'tenKhachHang' => 'required|string|max:255',
            'diaChi'       => 'required|string|max:255',
            'soDienThoai'  => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $khachHang = $user->khachHang;

        // Cập nhật thông tin khách hàng
        $khachHang->update([
            'tenKhachHang' => $request->tenKhachHang,
            'diaChi'       => $request->diaChi,
            'soDienThoai'  => $request->soDienThoai,
        ]);

        $selectedIds = session('checkout_items');

        if (!$selectedIds || count($selectedIds) == 0) {
            return back()->with('error', 'Chưa chọn sản phẩm checkout');
        }

        DB::beginTransaction();

        try {
            $items = DonTrongGioHang::whereIn('idDonTrongGioHang', $selectedIds)
                ->with('sanPham')
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                throw new \Exception('Giỏ hàng trống');
            }

            // Trừ kho
            foreach ($items as $item) {
                $tonKho = HangTonKho::where('idSanPham', $item->idSanPham)
                    ->lockForUpdate()
                    ->first();

                if (!$tonKho || $tonKho->soLuong < $item->soLuong) {
                    throw new \Exception("Sản phẩm {$item->sanPham->tenSanPham} không đủ tồn kho");
                }

                $tonKho->decrement('soLuong', $item->soLuong);

                $totalTon = HangTonKho::where('idSanPham', $item->idSanPham)->sum('soLuong');
                SanPham::where('idSanPham', $item->idSanPham)
                    ->update(['soLuong' => $totalTon]);
            }

            // Tính tiền + gán giaSauGiam
            [$total, $discount] = $this->calculateCart($items);

            // Tạo đơn hàng
            $donHang = DonHang::create([
                'ngayLap'       => Carbon::now(),
                'tongThanhTien' => $total,
                'giamGia'       => $discount,
                'trangThai'     => 'Đang xử lý',
                'idNguoiDung'   => $user->idNguoiDung,
                'idKhachHang'   => $khachHang->idKhachHang,
            ]);

            // ==================== TẠO CHI TIẾT ĐƠN HÀNG ====================
            foreach ($items as $item) {
                // Ưu tiên giaSauGiam đã tính trong calculateCart
                $donGiaThucTe = $item->giaSauGiam ?? $item->gia ?? $item->sanPham->gia ?? 0;

                ChiTietDonHang::create([
                    'soLuong'   => $item->soLuong,
                    'donGia'    => $donGiaThucTe,           // Giá sau giảm thực tế
                    'idDonHang' => $donHang->idDonHang,
                    'idSanPham' => $item->idSanPham,
                ]);
            }

            // Tạo thanh toán
            ThanhToan::create([
                'idDonHang'  => $donHang->idDonHang,
                'soTien'     => $total,
                'phuongThuc' => $request->phuongThuc,
                'trangThai'  => 'Hoàn thành',
            ]);

            // Xóa item đã mua
            DonTrongGioHang::whereIn('idDonTrongGioHang', $selectedIds)->delete();
            session()->forget('checkout_items');

            DB::commit();

            return redirect()->route('home')
                ->with('success', 'Đặt hàng thành công! 🎉');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function calculateCart($items)
    {
        $total = 0;
        $discount = 0;

        foreach ($items as $item) {
            $giaGoc = $item->sanPham->gia ?? 0;

            // Ưu tiên giá đã lưu trong giỏ hàng (đã giảm giá khi add)
            if ($item->gia) {
                $giaMoi = $item->gia;
            } else {
                $giamGia = ChuongTrinhGiamGia::where('theLoai', $item->sanPham->theLoai)
                    ->where('ngayBatDau', '<=', Carbon::now())
                    ->where('ngayKetThuc', '>=', Carbon::now())
                    ->first();

                $giaMoi = $giamGia 
                    ? round($giaGoc * (1 - $giamGia->phanTramGiam / 100)) 
                    : $giaGoc;
            }

            // Gán thuộc tính động để dùng sau
            $item->giaSauGiam = $giaMoi;

            $total += $giaMoi * $item->soLuong;
            $discount += ($giaGoc - $giaMoi) * $item->soLuong;
        }

        return [round($total), round($discount)];
    }

    private function getOrCreateCart()
    {
        $user = Auth::user();
        $khachHang = $user->khachHang;

        $cart = GioHang::where('idKhachHang', $khachHang->idKhachHang)->first();

        if (!$cart) {
            $cart = GioHang::create(['idKhachHang' => $khachHang->idKhachHang]);
        }

        return $cart;
    }
}