<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use Illuminate\Http\Request;
use App\Models\ChuongTrinhGiamGia;

class SanPhamController extends Controller
{
    public function show($id)
    {
        $sanPham = SanPham::findOrFail($id);

        // 🔥 TÍNH GIÁ GIẢM
        $giaSauGiam = $sanPham->gia;

        $giamGia = ChuongTrinhGiamGia::where('theLoai', $sanPham->theLoai)
            ->whereDate('ngayBatDau', '<=', now())
            ->whereDate('ngayKetThuc', '>=', now())
            ->first();

        if ($giamGia) {
            $giaSauGiam = $sanPham->gia * (1 - $giamGia->phanTramGiam / 100);
        }

        return view('sanpham.show', compact(
            'sanPham',
            'giaSauGiam',
            'giamGia'
        ));
    }
}