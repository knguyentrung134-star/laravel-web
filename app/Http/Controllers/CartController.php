<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\GioHang;
use App\Models\DonTrongGioHang;
use App\Models\SanPham;
use App\Models\KhachHang;                    // ← Thêm dòng này
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;      // ← Thêm dòng này

class CartController extends Controller
{
    // Lấy hoặc tạo giỏ hàng cho khách hàng hiện tại
    private function getOrCreateCart()
    {
        $user = Auth::user();

        // ==================== FIX TỰ ĐỘNG TẠO KHÁCH HÀNG ====================
        if (!$user->khachHang) {
            // Tạo bản ghi khách hàng mới
            $khachHang = KhachHang::create([
                'tenKhachHang' => $user->tenNguoiDung ?? 'Khách hàng ' . $user->idNguoiDung,
                'email'        => $user->email ?? null,
                'soDienThoai'  => null,
                'diaChi'       => null,
                'idNguoiDung'  => $user->idNguoiDung,     // Liên kết với bảng nguoidung
            ]);

            // Reload relationship để lấy dữ liệu mới nhất
            $user->load('khachHang');
        }

        $khachHangId = $user->khachHang->idKhachHang;

        // Tạo hoặc lấy giỏ hàng
        $cart = GioHang::firstOrCreate(
            ['idKhachHang' => $khachHangId],
            ['ngayTao' => now()->toDateString()]
        );

        return $cart;
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();

        $items = DonTrongGioHang::where('idGioHang', $cart->idGioHang)
                    ->with('sanPham')
                    ->get();

        $total = $items->sum(function ($item) {
            return $item->soLuong * $item->sanPham->gia;
        });

        return view('cart.index', compact('items', 'total'));
    }

    public function add(AddToCartRequest $request)
    {
        $cart = $this->getOrCreateCart();
        $sanPham = SanPham::findOrFail($request->idSanPham);

        $existing = DonTrongGioHang::where('idGioHang', $cart->idGioHang)
                    ->where('idSanPham', $sanPham->idSanPham)
                    ->first();

        if ($existing) {
            $existing->soLuong += $request->soLuong ?? 1;
            $existing->save();
        } else {
            DonTrongGioHang::create([
                'soLuong'   => $request->soLuong ?? 1,
                'idGioHang' => $cart->idGioHang,
                'idSanPham' => $sanPham->idSanPham,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'id'      => 'required|exists:dontronggiohang,idDonTrongGioHang', 
            'soLuong' => 'required|integer|min:1'
        ]);

        $item = DonTrongGioHang::findOrFail($request->id);
        $item->soLuong = $request->soLuong;
        $item->save();

        return redirect()->route('cart.index')->with('success', 'Cập nhật số lượng thành công!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:dontronggiohang,idDonTrongGioHang'
        ]);

        DonTrongGioHang::destroy($request->id);

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ!');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        DonTrongGioHang::where('idGioHang', $cart->idGioHang)->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}