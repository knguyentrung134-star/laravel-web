<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index()
{
    $khachHang = Auth::user()->khachHang;

    $donHangs = DonHang::where('idKhachHang', $khachHang->idKhachHang)
        ->with(['chiTietDonHangs.sanPham', 'thanhToan'])
        ->orderBy('ngayLap', 'desc')
        ->get();

    return view('customer.order_history', compact('donHangs')); // hoặc tên view của bạn
}
    // ==================== HỦY ĐƠN HÀNG ====================
    public function huyDonHang(Request $request, $idDonHang)
    {
        $request->validate([
            'ly_do_huy' => 'required|string|max:255'
        ]);

        $khachHang = Auth::user()->khachHang;

        $donHang = DonHang::where('idDonHang', $idDonHang)
            ->where('idKhachHang', $khachHang?->idKhachHang)
            ->firstOrFail();

        // Kiểm tra có được phép hủy không
        if (!$donHang->canCancel()) {
            return redirect()->back()
                ->with('error', 'Đơn hàng này không thể hủy nữa.');
        }

        // Hủy đơn
        $donHang->cancel($request->ly_do_huy);

        return redirect()->route('order.history')
            ->with('success', 'Đơn hàng #' . $donHang->idDonHang . ' đã được hủy thành công.');
    }
}