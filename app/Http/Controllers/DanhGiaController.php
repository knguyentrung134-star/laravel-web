<?php

namespace App\Http\Controllers;

use App\Models\DanhGiaSanPham;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DanhGiaController extends Controller
{
    public function store(Request $request, SanPham $sanpham)
    {
        $request->validate([
            'noiDung' => 'required|string|max:200',
            'soSao'   => 'required|integer|min:1|max:5',
        ]);

        // Kiểm tra khách hàng đã mua sản phẩm này chưa (tùy chọn nâng cao)
        // Hiện tại cho phép đánh giá miễn là đã đăng nhập

        DanhGiaSanPham::create([
            'noiDung'     => $request->noiDung,
            'soSao'       => $request->soSao,
            'idNguoiDung' => Auth::id(),
            'idSanPham'   => $sanpham->idSanPham,
        ]);

        return redirect()->back()
            ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm! ⭐');
    }
}