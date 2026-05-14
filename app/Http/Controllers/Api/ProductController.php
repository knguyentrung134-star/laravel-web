<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = SanPham::paginate(20);

        return response()->json(
            $products,
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function show($id)
    {
        $product = SanPham::with('danhGias')
            ->findOrFail($id);

        return response()->json(
            $product,
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function store(Request $request)
    {
        return response()->json(
            ['message' => 'Chức năng đang phát triển'],
            501,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}