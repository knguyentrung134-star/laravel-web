<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\ThanhToan;
use App\Models\DonTrongGioHang;
use App\Models\GioHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $khachHang = $user->khachHang;

        if (!$khachHang) {
            return redirect()->route('cart.index')->with('error', 'Bạn chưa có thông tin khách hàng.');
        }

        $cart = GioHang::where('idKhachHang', $khachHang->idKhachHang)->first();
        if (!$cart || $cart->donTrongGioHangs()->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $items = $cart->donTrongGioHangs()->with('sanPham')->get();
        $total = $items->sum(fn($item) => $item->soLuong * $item->sanPham->gia);

        return view('checkout.index', compact('items', 'total', 'khachHang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phuongThuc' => 'required|in:TienMat,ChuyenKhoan,TheTinDung,ViDienTu',
        ]);

        $user = Auth::user();
        $khachHang = $user->khachHang;

        $cart = GioHang::where('idKhachHang', $khachHang->idKhachHang)->first();
        if (!$cart || $cart->donTrongGioHangs()->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $items = $cart->donTrongGioHangs()->with('sanPham')->get();
        $tongTien = $items->sum(fn($item) => $item->soLuong * $item->sanPham->gia);

        DB::beginTransaction();

        try {
            // Tạo đơn hàng
            $donHang = DonHang::create([
                'ngayLap' => now()->toDateString(),
                'tongThanhTien' => $tongTien,
                'trangThai' => 'Đang xử lý',
                'idNguoiDung' => $user->idNguoiDung,
                'idKhachHang' => $khachHang->idKhachHang,
            ]);

            // Tạo chi tiết đơn hàng
            foreach ($items as $item) {
                ChiTietDonHang::create([
                    'soLuong' => $item->soLuong,
                    'donGia' => $item->sanPham->gia,
                    'idDonHang' => $donHang->idDonHang,
                    'idSanPham' => $item->idSanPham,
                ]);

                // Giảm số lượng tồn kho (nếu có)
                // $hangTon = HangTonKho::where('idSanPham', $item->idSanPham)->first();
                // if ($hangTon) $hangTon->decrement('soLuong', $item->soLuong);
            }

            // Tạo thanh toán
            ThanhToan::create([
                'idDonHang' => $donHang->idDonHang,
                'ngayThanhToan' => now()->toDateString(),
                'soTien' => $tongTien,
                'phuongThuc' => $request->phuongThuc,
                'trangThai' => 'Hoàn thành',
            ]);

            // Xóa giỏ hàng
            DonTrongGioHang::where('idGioHang', $cart->idGioHang)->delete();

            DB::commit();

            return redirect()->route('home')
                ->with('success', 'Đặt hàng thành công! Mã đơn hàng: #' . $donHang->idDonHang);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }
}