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

    $maGiamGia = trim($request->input('ma_giam_gia'));

    [$total, $discount] = $this->calculateCart($items, $maGiamGia);

    // ==================== THÔNG BÁO ====================
    if ($maGiamGia !== null && $maGiamGia !== '') {
        if ($discount > 0) {
            session()->flash('success', '✅ Áp dụng mã giảm giá thành công!');
        } else {
            session()->flash('error', '❌ Không tìm thấy khuyến mãi hợp lệ.');
        }
    }
    // =================================================

    $khachHang = Auth::user()->khachHang;

    return view('checkout.index', compact('items', 'total', 'discount', 'khachHang', 'maGiamGia'));
}

// =========================
private function calculateCart($items, $maGiamGia = null)
{
    $total = 0;
    $discount = 0;

    foreach ($items as $item) {
        if (!$item->sanPham) continue;

        $giaGoc = $item->sanPham->gia ?? 0;
        $giaMoi = $giaGoc;

        if ($maGiamGia) {
            $khuyenMai = KhuyenMai::whereDate('ngayBatDau', '<=', Carbon::today())
                ->whereDate('ngayKetThuc', '>=', Carbon::today())
                ->first();

            if ($khuyenMai) {
                // Lấy % giảm từ cột moTaKhuyenMai (ví dụ: "80%" → 80)
                $moTa = $khuyenMai->moTaKhuyenMai;
                preg_match('/(\d+)/', $moTa, $matches);
                $phanTram = isset($matches[1]) ? (int)$matches[1] : 0;

                if ($phanTram > 0) {
                    $giaMoi = round($giaGoc * (1 - $phanTram / 100));
                }
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

        // ==================== LƯU CHI TIẾT ĐƠN HÀNG (QUAN TRỌNG) ====================
        foreach ($items as $item) {
            $giaGoc = $item->sanPham->gia ?? 0;
            // Ưu tiên giá đã giảm từ calculateCart
            $donGiaThucTe = $item->giaSauGiam ?? $giaGoc;

            ChiTietDonHang::create([
                'soLuong'   => $item->soLuong,
                'donGia'    => $donGiaThucTe,        // ← Đây là giá sau giảm
                'idDonHang' => $donHang->idDonHang,
                'idSanPham' => $item->idSanPham,
            ]);
        }

        ThanhToan::create([
            'idDonHang'  => $donHang->idDonHang,
            'soTien'     => $total,
            'phuongThuc' => $request->phuongThuc,
            'trangThai'  => 'Hoàn thành',
        ]);

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
        $user = Auth::user();
        $khachHang = $user->khachHang;

        $cart = GioHang::where('idKhachHang', $khachHang->idKhachHang)->first();

        if (!$cart) {
            $cart = GioHang::create(['idKhachHang' => $khachHang->idKhachHang]);
        }

        return $cart;
    }
}