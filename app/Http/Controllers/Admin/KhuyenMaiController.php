<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;

class KhuyenMaiController extends Controller
{
    public function index()
    {
        $khuyenMais = KhuyenMai::orderBy('idKhuyenMai', 'desc')->paginate(10);
        return view('admin.khuyenmai.index', compact('khuyenMais'));
    }

    public function create()
    {
        return view('admin.khuyenmai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'moTaKhuyenMai' => 'required|string|max:200',
            'ngayBatDau'    => 'required|date',
            'ngayKetThuc'   => 'required|date|after_or_equal:ngayBatDau',
        ]);

        KhuyenMai::create($request->all());

        return redirect()->route('admin.khuyenmai.index')
            ->with('success', 'Thêm khuyến mãi thành công!');
    }

    public function edit(KhuyenMai $khuyenmai)
    {
        return view('admin.khuyenmai.edit', compact('khuyenmai'));
    }

    public function update(Request $request, KhuyenMai $khuyenmai)
    {
        $request->validate([
            'moTaKhuyenMai' => 'required|string|max:200',
            'ngayBatDau'    => 'required|date',
            'ngayKetThuc'   => 'required|date|after_or_equal:ngayBatDau',
        ]);

        $khuyenmai->update($request->all());

        return redirect()->route('admin.khuyenmai.index')
            ->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    public function destroy(KhuyenMai $khuyenmai)
    {
        $khuyenmai->delete();
        return redirect()->route('admin.khuyenmai.index')
            ->with('success', 'Xóa khuyến mãi thành công!');
    }
}