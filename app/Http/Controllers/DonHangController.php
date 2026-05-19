<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonHang;

class DonHangController extends Controller
{
    public function huyDonHang($id, Request $request)
    {
        $donHang = DonHang::findOrFail($id);

        // Chỉ chủ đơn mới được hủy
        if ($donHang->idKhachHang != auth()->user()->idKhachHang) {
            abort(403, 'Bạn không có quyền hủy đơn này');
        }

        // Không cho hủy nếu hoàn thành
        if (!$donHang->canCancel()) {
            return back()->with('error', 'Đơn hàng này không thể hủy');
        }

        $donHang->cancel($request->ly_do_huy);

        return back()->with('success', 'Hủy đơn hàng thành công');
    }
}