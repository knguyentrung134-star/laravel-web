<?php

namespace App\Http\Controllers;

use App\Models\GioHang;
use App\Models\DonTrongGioHang;
use App\Models\SanPham;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ChuongTrinhGiamGia;

class CartController extends Controller
{
    // =========================
    // LẤY / TẠO GIỎ HÀNG
    // =========================
    private function getOrCreateCart()
    {
        $user = Auth::user();

        if (!$user->khachHang) {
            KhachHang::create([
                'tenKhachHang' => $user->tenNguoiDung ?? 'Khách hàng',
                'email'        => $user->email ?? null,
                'soDienThoai'  => null,
                'diaChi'       => null,
                'idNguoiDung'  => $user->idNguoiDung,
            ]);

            $user->load('khachHang');
        }

        return GioHang::firstOrCreate(
            ['idKhachHang' => $user->khachHang->idKhachHang],
            ['ngayTao' => Carbon::now()]
        );
    }

    // =========================
    // GIÁ SAU GIẢM - CHỈ DÙNG Ở CHECKOUT
    // =========================
    private function getDiscountedPrice(SanPham $sanPham, $maGiamGia = null)
    {
        $giaGoc = $sanPham->gia;

        // Nếu có mã giảm giá (từ checkout)
        if ($maGiamGia) {
            $khuyenMai = \App\Models\KhuyenMai::whereDate('ngayBatDau', '<=', Carbon::today())
                ->whereDate('ngayKetThuc', '>=', Carbon::today())
                ->orderBy('idKhuyenMai', 'desc')
                ->first();

            if ($khuyenMai) {
                return round($giaGoc * (1 - $khuyenMai->phanTramGiam / 100));
            }
        }

        // Khuyến mãi theo thể loại (dự phòng)
        $giamGiaTheLoai = ChuongTrinhGiamGia::where('theLoai', trim($sanPham->theLoai))
            ->whereDate('ngayBatDau', '<=', Carbon::today())
            ->whereDate('ngayKetThuc', '>=', Carbon::today())
            ->first();

        return $giamGiaTheLoai 
            ? round($giaGoc * (1 - $giamGiaTheLoai->phanTramGiam / 100))
            : $giaGoc;
    }

    // =========================
    // THÊM VÀO GIỎ - LUÔN LƯU GIÁ GỐC
    // =========================
    public function add(Request $request)
    {
        $request->validate([
            'idSanPham' => 'required|exists:sanpham,idSanPham'
        ]);

        $cart = $this->getOrCreateCart();
        $sanPham = SanPham::findOrFail($request->idSanPham);

        if ($sanPham->soLuong < 1) {
            return back()->with('error', 'Sản phẩm đã hết hàng!');
        }

        $item = DonTrongGioHang::firstOrNew([
            'idGioHang' => $cart->idGioHang,
            'idSanPham' => $sanPham->idSanPham,
        ]);

        $item->soLuong = ($item->soLuong ?? 0) + 1;
        $item->gia = $sanPham->gia;        // Luôn lưu giá gốc
        $item->save();

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // =========================
    // HIỂN THỊ GIỎ HÀNG
    // =========================
    public function index()
    {
        $cart = $this->getOrCreateCart();

        $items = DonTrongGioHang::where('idGioHang', $cart->idGioHang)
            ->with('sanPham')
            ->get();

        [$total, $discount] = $this->calculateCart($items);

        return view('cart.index', compact('items', 'total', 'discount'));
    }

    // =========================
    // TÍNH TỔNG GIỎ HÀNG (không giảm)
    // =========================
    private function calculateCart($items)
    {
        $total = 0;
        $discount = 0;

        foreach ($items as $item) {
            if (!$item->sanPham) continue;

            $giaGoc = $item->sanPham->gia ?? 0;
            $giaHienTai = $item->gia ?? $giaGoc;   // Luôn dùng giá gốc

            $total += $giaHienTai * $item->soLuong;
        }

        return [round($total), round($discount)];
    }

    // =========================
    // CẬP NHẬT SỐ LƯỢNG
    // =========================
    public function update(Request $request)
    {
        $item = DonTrongGioHang::findOrFail($request->id);
        $sanPham = SanPham::find($item->idSanPham);

        if (!$sanPham) {
            return back()->with('error', 'Sản phẩm không tồn tại');
        }

        if ($request->action == 'increase') {
            if ($item->soLuong < $sanPham->soLuong) {
                $item->soLuong += 1;
            } else {
                return back()->with('error', 'Không đủ hàng trong kho!');
            }
        } elseif ($request->action == 'decrease') {
            if ($item->soLuong > 1) {
                $item->soLuong -= 1;
            }
        }

        $item->gia = $sanPham->gia;   // Luôn giữ giá gốc
        $item->save();

        return back();
    }

    // Các hàm khác giữ nguyên
    public function remove($id)
    {
        $item = DonTrongGioHang::find($id);
        if ($item) $item->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        DonTrongGioHang::where('idGioHang', $cart->idGioHang)->delete();

        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    public function checkout(Request $request)
    {
        $selectedIds = $request->input('selected_items', []);

        if (empty($selectedIds)) {
            return redirect()->route('cart.index')
                ->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
        }

        session(['checkout_items' => $selectedIds]);

        return redirect()->route('checkout.index');
    }
}