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

        return response()->json($products);
    }

    public function show($id)
    {
        $product = SanPham::with('danhGias')
            ->findOrFail($id);

        return response()->json($product);
    }

    public function store(Request $request)
    {
        // TODO: Thêm validation và logic sau
        return response()->json(['message' => 'Chức năng đang phát triển'], 501);
    }

    // update, destroy tạm thời để sau
}