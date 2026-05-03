<?php

namespace App\Http\Controllers;

use App\Models\TraHang;
use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraHangController extends Controller
{
    public function index()
{
    $khachHangId = Auth::user()->khachHang->idKhachHang ?? null;

    if (!$khachHangId) {
        return redirect()->route('home')->with('error', 'Bạn chưa có thông tin khách hàng.');
    }

    // Lấy danh sách đơn hàng có thể đổi/trả
    $donHangs = DonHang::where('idKhachHang', $khachHangId)
                ->whereIn('trangThai', ['Hoàn thành', 'Đang giao'])
                ->with('chiTietDonHangs.sanPham')
                ->orderBy('idDonHang', 'desc')
                ->get();

    // Lấy lịch sử yêu cầu đổi/trả
    $traHangs = TraHang::whereHas('donHang', function($query) use ($khachHangId) {
                    $query->where('idKhachHang', $khachHangId);
                })
                ->with('donHang.chiTietDonHangs.sanPham')
                ->orderBy('idTraHang', 'desc')
                ->get();

    return view('customer.tra_hang', compact('donHangs', 'traHangs'));
}

    public function store(Request $request)
    {
        $request->validate([
            'idDonHang' => 'required|exists:donhang,idDonHang',
            'lyDo'      => 'required|string|max:200',
        ]);

        $donHang = DonHang::findOrFail($request->idDonHang);

        // Kiểm tra đơn hàng có phải của khách hàng hiện tại không
        if ($donHang->idKhachHang !== Auth::user()->khachHang->idKhachHang) {
            return back()->with('error', 'Bạn không có quyền trả đơn hàng này.');
        }

        TraHang::create([
            'ngayTra'   => now()->toDateString(),
            'lyDo'      => $request->lyDo,
            'idDonHang' => $request->idDonHang,
        ]);

        // Cập nhật trạng thái đơn hàng
        $donHang->update(['trangThai' => 'Yêu cầu trả hàng']);

        return redirect()->route('order.history')
            ->with('success', 'Yêu cầu đổi/trả hàng đã được gửi thành công. Chúng tôi sẽ liên hệ với bạn sớm nhất!');
    }
}