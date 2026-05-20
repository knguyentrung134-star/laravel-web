<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\ThanhToan;
use App\Models\DonTrongGioHang;
use App\Models\GioHang;
use App\Models\HangTonKho;
use App\Models\SanPham;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // =========================
    // HIỂN THỊ TRANG THANH TOÁN
    // =========================
    public function index(Request $request)
{
    $selectedIds = session('checkout_items', []);

    if (empty($selectedIds)) {
        return redirect()->route('cart.index')->with('error', 'Không có sản phẩm nào!');
    }

    $cart = $this->getOrCreateCart();

    $items = DonTrongGioHang::where('idGioHang', $cart->idGioHang)
        ->whereIn('idDonTrongGioHang', $selectedIds)
        ->with('sanPham')
        ->get();

    if ($items->isEmpty()) {
        session()->forget('checkout_items');
        return redirect()->route('cart.index')->with('error', 'Sản phẩm không tồn tại!');
    }

    $maGiamGia = trim($request->input('ma_giam_gia', ''));

    [$total, $discount] = $this->calculateCart($items, $maGiamGia);

    // Thông báo
    if ($maGiamGia !== '') {
        if ($discount > 0) {
            session()->flash('success', '✅ Áp dụng mã giảm giá thành công!');
        } else {
            session()->flash('error', '❌ Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }
    }

    $khachHang = Auth::user()->khachHang;

    return view('checkout.index', compact('items', 'total', 'discount', 'khachHang', 'maGiamGia'));
}

    // =========================
    // TÍNH TOÁN GIÁ (ĐÃ CẢI TIẾN)
    // =========================
   private function calculateCart($items, $maGiamGia = null)
{
    $total = 0;
    $discount = 0;

    $khuyenMai = null;

    if ($maGiamGia) {
        $khuyenMai = KhuyenMai::where('maKhuyenMai', trim($maGiamGia))
            ->where('trangThai', 1)
            ->whereDate('ngayBatDau', '<=', Carbon::today())
            ->whereDate('ngayKetThuc', '>=', Carbon::today())
            ->first();
    }

    foreach ($items as $item) {
        if (!$item->sanPham) continue;

        $giaGoc = (int) ($item->sanPham->gia ?? 0);
        $giaMoi = $giaGoc;

        if ($khuyenMai) {
            $phanTram = (int) ($khuyenMai->phanTramGiam ?? 0);
            if ($phanTram > 0) {
                $giaMoi = round($giaGoc * (1 - $phanTram / 100));
            }
        }

        $item->giaSauGiam = $giaMoi;

        $total += $giaMoi * $item->soLuong;
        $discount += ($giaGoc - $giaMoi) * $item->soLuong;
    }

    return [round($total), round($discount)];
}

    // =========================
    // ĐẶT HÀNG
    // =========================
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

        $khachHang->update([
            'tenKhachHang' => $request->tenKhachHang,
            'diaChi'       => $request->diaChi,
            'soDienThoai'  => $request->soDienThoai,
        ]);

        $selectedIds = session('checkout_items', []);

        if (empty($selectedIds)) {
            return back()->with('error', 'Chưa chọn sản phẩm thanh toán');
        }

        DB::beginTransaction();

        try {
            $items = DonTrongGioHang::whereIn('idDonTrongGioHang', $selectedIds)
                ->with('sanPham')
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                throw new \Exception('Không tìm thấy sản phẩm trong giỏ hàng');
            }

            // Trừ tồn kho
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

            $maGiamGia = $request->input('ma_giam_gia');
            [$total, $discount] = $this->calculateCart($items, $maGiamGia);

            // Tạo đơn hàng
            $donHang = DonHang::create([
                'ngayLap'       => Carbon::now(),
                'tongThanhTien' => $total,
                'giamGia'       => $discount,
                'trangThai'     => 'Đang xử lý',
                'idNguoiDung'   => $user->idNguoiDung,
                'idKhachHang'   => $khachHang->idKhachHang,
            ]);

            // ==================== LƯU CHI TIẾT ĐƠN HÀNG ====================
            foreach ($items as $item) {
                $donGiaThucTe = $item->giaSauGiam ?? $item->sanPham->gia ?? 0;

                ChiTietDonHang::create([
                    'soLuong'    => $item->soLuong,
                    'donGia'     => $donGiaThucTe,           // ← Giá sau giảm (quan trọng nhất)
                    'idDonHang'  => $donHang->idDonHang,
                    'idSanPham'  => $item->idSanPham,
                ]);
            }

            // Thanh toán
            ThanhToan::create([
                'idDonHang'  => $donHang->idDonHang,
                'soTien'     => $total,
                'phuongThuc' => $request->phuongThuc,
                'trangThai'  => 'Hoàn thành',
            ]);

            // Xóa khỏi giỏ
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

    private function getOrCreateCart()
    {
        $khachHang = Auth::user()->khachHang;

        return GioHang::firstOrCreate(['idKhachHang' => $khachHang->idKhachHang]);
    }
}