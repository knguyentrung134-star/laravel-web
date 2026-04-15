<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Dùng paginate cho đồng bộ với search
        $sanPhams = SanPham::paginate(12);

        return view('home', compact('sanPhams'));
    }

    public function search(Request $request)
    {
        $keyword = $request->q;

        $sanPhams = SanPham::when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('tenSanPham', 'LIKE', "%{$keyword}%")
                      ->orWhere('moTa', 'LIKE', "%{$keyword}%");
                });
            })
            ->paginate(12)
            ->appends(['q' => $keyword]); // giữ query khi chuyển trang

        return view('home', compact('sanPhams', 'keyword'));
    }
}