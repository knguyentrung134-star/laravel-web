<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use Illuminate\Http\Request;

class DonHangController extends Controller
{
    public function index()
    {
        $donHangs = DonHang::with(['khachHang', 'chiTietDonHangs.sanPham'])
                    ->latest('idDonHang')
                    ->paginate(10);

        return view('admin.donhang.index', compact('donHangs'));
    }

    public function show(DonHang $donhang)
    {
        $donhang->load(['khachHang', 'chiTietDonHangs.sanPham', 'thanhToans']);
        return view('admin.donhang.show', compact('donhang'));
    }

    public function update(Request $request, DonHang $donhang)
    {
        $request->validate([
            'trangThai' => 'required|in:Đang xử lý,Đã xác nhận,Đang giao,Hoàn thành,Đã hủy',
        ]);

        $donhang->update(['trangThai' => $request->trangThai]);

        return redirect()->route('admin.donhang.index')
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}