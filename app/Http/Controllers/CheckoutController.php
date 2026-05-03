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
        $user = Auth::user();
        $khachHang = $user->khachHang;

        if (!$khachHang) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để thanh toán');
        }

        $selectedIds = $request->selected;

        if (!$selectedIds || count($selectedIds) == 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Vui lòng chọn sản phẩm để thanh toán');
        }

        $items = DonTrongGioHang::whereIn('idDonTrongGioHang', $selectedIds)
            ->with('sanPham')
            ->get();

        [$total, $discount] = $this->calculateCart($items);

        session(['checkout_items' => $selectedIds]);

        return view('checkout.index', compact(
            'items',
            'total',
            'discount',
            'khachHang'
        ));
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

        // 🔥 cập nhật thông tin KH
        $khachHang->update([
            'tenKhachHang' => $request->tenKhachHang,
            'diaChi'       => $request->diaChi,
            'soDienThoai'  => $request->soDienThoai,
        ]);

        $selectedIds = session('checkout_items');

        DB::beginTransaction();

        try {
            $items = DonTrongGioHang::whereIn('idDonTrongGioHang', $selectedIds)
                ->with('sanPham')
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                throw new \Exception('Giỏ hàng trống');
            }

            // 🔥 TRỪ KHO (FIX CHUẨN)
            foreach ($items as $item) {

                // lock kho
                $tonKho = HangTonKho::where('idSanPham', $item->idSanPham)
                    ->lockForUpdate()
                    ->first();

                // ❗ FIX: nếu chưa có kho → coi như 0
                if (!$tonKho) {
                    throw new \Exception("Sản phẩm {$item->sanPham->tenSanPham} chưa có trong kho");
                }

                $soLuongTon = $tonKho->soLuong;

                // ❗ chặn âm kho
                if ($soLuongTon < $item->soLuong) {
                    throw new \Exception(
                        "Sản phẩm {$item->sanPham->tenSanPham} chỉ còn {$soLuongTon}"
                    );
                }

                // trừ kho
                $tonKho->decrement('soLuong', $item->soLuong);

                // đồng bộ lại tổng tồn → bảng sản phẩm
                $totalTon = HangTonKho::where('idSanPham', $item->idSanPham)
                    ->sum('soLuong');

                SanPham::where('idSanPham', $item->idSanPham)
                    ->update(['soLuong' => $totalTon]);
            }

            // 🔥 tính tiền
            [$total, $discount] = $this->calculateCart($items);

            $donHang = DonHang::create([
                'ngayLap'        => Carbon::now(),
                'tongThanhTien'  => $total,
                'giamGia'        => $discount,
                'trangThai'      => 'Đang xử lý',
                'idNguoiDung'    => $user->idNguoiDung,
                'idKhachHang'    => $khachHang->idKhachHang,
            ]);

            foreach ($items as $item) {
                ChiTietDonHang::create([
                    'soLuong'    => $item->soLuong,
                    'donGia'     => $item->giaSauGiam,
                    'idDonHang'  => $donHang->idDonHang,
                    'idSanPham'  => $item->idSanPham,
                ]);
            }

            ThanhToan::create([
                'idDonHang'   => $donHang->idDonHang,
                'soTien'      => $total,
                'phuongThuc'  => $request->phuongThuc,
                'trangThai'   => 'Hoàn thành',
            ]);

            // xóa giỏ hàng
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

            $giamGia = ChuongTrinhGiamGia::where('theLoai', $item->sanPham->theLoai)
                ->where('ngayBatDau', '<=', Carbon::now())
                ->where('ngayKetThuc', '>=', Carbon::now())
                ->first();

            if ($giamGia) {
                $giaMoi = $giaGoc * (1 - $giamGia->phanTramGiam / 100);
                $discount += ($giaGoc - $giaMoi) * $item->soLuong;
            } else {
                $giaMoi = $giaGoc;
            }

            $item->giaSauGiam = round($giaMoi);
            $total += $item->giaSauGiam * $item->soLuong;
        }

        return [round($total), round($discount)];
    }
}