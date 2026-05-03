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

    // ✅ THÊM SẢN PHẨM
    public function add(Request $request)
    {
        $request->validate([
            'idSanPham' => 'required|exists:sanpham,idSanPham',
        ]);

        $cart = $this->getOrCreateCart();
        $sanPham = SanPham::findOrFail($request->idSanPham);

        if ($sanPham->soLuong < 1) {
            return back()->with('error', 'Sản phẩm đã hết hàng!');
        }

        $item = DonTrongGioHang::where('idGioHang', $cart->idGioHang)
            ->where('idSanPham', $sanPham->idSanPham)
            ->first();

        if ($item) {
            $item->soLuong += 1;
            $item->save();
        } else {
            DonTrongGioHang::create([
                'idGioHang' => $cart->idGioHang,
                'idSanPham' => $sanPham->idSanPham,
                'soLuong'   => 1,
            ]);
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // ✅ HIỂN THỊ GIỎ HÀNG
    public function index()
    {
        $cart = $this->getOrCreateCart();

        $items = DonTrongGioHang::where('idGioHang', $cart->idGioHang)
            ->with('sanPham')
            ->get();

        if ($items->isEmpty()) {
            return view('cart.index', compact('items'));
        }

        [$total, $discount] = $this->calculateCart($items);

        return view('cart.index', compact('items', 'total', 'discount'));
    }

    // ✅ XÓA 1 SẢN PHẨM
    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:dontronggiohang,idDonTrongGioHang'
        ]);

        DonTrongGioHang::destroy($request->id);

        return back()->with('success', 'Đã xóa!');
    }

    // ✅ XÓA TOÀN BỘ
    public function clear()
    {
        $cart = $this->getOrCreateCart();

        DonTrongGioHang::where('idGioHang', $cart->idGioHang)->delete();

        return back()->with('success', 'Đã xóa toàn bộ!');
    }

    // ✅ TĂNG / GIẢM SỐ LƯỢNG (FIX CHÍNH)
    public function update(Request $request)
    {
        $item = DonTrongGioHang::findOrFail($request->id);
        $sanPham = SanPham::find($item->idSanPham);

        if (!$sanPham) {
            return back()->with('error', 'Sản phẩm không tồn tại');
        }

        // Tăng
        if ($request->action == 'increase') {
            if ($item->soLuong < $sanPham->soLuong) {
                $item->soLuong += 1;
            } else {
                return back()->with('error', 'Không đủ hàng trong kho!');
            }
        }

        // Giảm
        if ($request->action == 'decrease') {
            if ($item->soLuong > 1) {
                $item->soLuong -= 1;
            }
        }

        $item->save();

        return back();
    }

    // ✅ TÍNH TIỀN
    private function calculateCart($items)
    {
        $total = 0;
        $discount = 0;

        foreach ($items as $item) {
            $giaGoc = $item->sanPham->gia;

            $giamGia = ChuongTrinhGiamGia::where('theLoai', $item->sanPham->theLoai)
                ->whereDate('ngayBatDau', '<=', now())
                ->whereDate('ngayKetThuc', '>=', now())
                ->first();

            if ($giamGia) {
                $giaSauGiam = $giaGoc * (1 - $giamGia->phanTramGiam / 100);
                $discount += ($giaGoc - $giaSauGiam) * $item->soLuong;
            } else {
                $giaSauGiam = $giaGoc;
            }

            $item->giaSauGiam = round($giaSauGiam);
            $total += $item->giaSauGiam * $item->soLuong;
        }

        return [round($total), round($discount)];
    }
}