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

    private function getDiscountedPrice(SanPham $sanPham)
    {
        $giaGoc = $sanPham->gia;

        $giamGia = ChuongTrinhGiamGia::where('theLoai', $sanPham->theLoai)
            ->whereDate('ngayBatDau', '<=', now())
            ->whereDate('ngayKetThuc', '>=', now())
            ->first();

        return $giamGia 
            ? round($giaGoc * (1 - $giamGia->phanTramGiam / 100)) 
            : $giaGoc;
    }

    // ✅ THÊM SẢN PHẨM VÀO GIỎ
    public function add(Request $request)
    {
        $request->validate(['idSanPham' => 'required|exists:sanpham,idSanPham']);

        $cart = $this->getOrCreateCart();
        $sanPham = SanPham::findOrFail($request->idSanPham);

        if ($sanPham->soLuong < 1) {
            return back()->with('error', 'Sản phẩm đã hết hàng!');
        }

        $giaSauGiam = $this->getDiscountedPrice($sanPham);

        $item = DonTrongGioHang::firstOrNew([
            'idGioHang' => $cart->idGioHang,
            'idSanPham' => $sanPham->idSanPham,
        ]);

        $item->soLuong = ($item->soLuong ?? 0) + 1;
        $item->gia = $giaSauGiam;
        $item->save();

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // ✅ HIỂN THỊ GIỎ HÀNG
    public function index()
    {
        $cart = $this->getOrCreateCart();

        $items = DonTrongGioHang::where('idGioHang', $cart->idGioHang)
            ->with('sanPham')
            ->get();

        [$total, $discount] = $this->calculateCart($items);

        return view('cart.index', compact('items', 'total', 'discount'));
    }

    // ✅ TÍNH TỔNG TIỀN
    private function calculateCart($items)
    {
        $total = 0;
        $discount = 0;

        foreach ($items as $item) {
            $giaGoc = $item->sanPham->gia;
            $giaHienTai = $item->gia ?? $this->getDiscountedPrice($item->sanPham);

            $total += $giaHienTai * $item->soLuong;

            if ($giaHienTai < $giaGoc) {
                $discount += ($giaGoc - $giaHienTai) * $item->soLuong;
            }
        }

        return [round($total), round($discount)];
    }

    // ✅ XÓA SẢN PHẨM
    public function remove(Request $request)
    {
        $request->validate(['id' => 'required|exists:dontronggiohang,idDonTrongGioHang']);
        DonTrongGioHang::destroy($request->id);
        return back()->with('success', 'Đã xóa!');
    }

    // ✅ XÓA TOÀN BỘ GIỎ HÀNG
    public function clear()
    {
        $cart = $this->getOrCreateCart();
        DonTrongGioHang::where('idGioHang', $cart->idGioHang)->delete();
        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    // ✅ CẬP NHẬT SỐ LƯỢNG
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

        $item->gia = $this->getDiscountedPrice($sanPham);
        $item->save();

        return back();
    }

    // ==================== THÊM MỚI: CHECKOUT ====================
    public function checkout(Request $request)
    {
        $selectedIds = $request->input('selected_items', []);

        if (empty($selectedIds)) {
            return redirect()->route('cart.index')
                ->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
        }

        // Lưu danh sách sản phẩm được chọn vào session
        session(['checkout_items' => $selectedIds]);

        return redirect()->route('checkout.index');
    }
}