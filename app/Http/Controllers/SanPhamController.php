<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use Illuminate\Http\Request;

class SanPhamController extends Controller
{
    public function show(SanPham $sanpham)
    {
        $sanpham->load('danhGias.nguoiDung');   // Load đánh giá + thông tin người dùng
        return view('sanpham.show', compact('sanpham'));
    }
}