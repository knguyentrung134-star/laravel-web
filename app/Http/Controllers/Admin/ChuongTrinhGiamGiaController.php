<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChuongTrinhGiamGia;

class ChuongTrinhGiamGiaController extends Controller
{
    
    public function index()
    {
        $ds = ChuongTrinhGiamGia::all();
        return view('admin.giamgia.index', compact('ds'));
    }

    public function create()
    {
        return view('admin.giamgia.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenChuongTrinh' => 'required',
            'theLoai' => 'required',
            'phanTramGiam' => 'required|numeric|min:1|max:100',
            'ngayBatDau' => 'required|date',
            'ngayKetThuc' => 'required|date|after_or_equal:ngayBatDau',
        ]);

        ChuongTrinhGiamGia::create($request->all());

        return redirect()->route('admin.giamgia.index')
            ->with('success', 'Thêm thành công!');
    }

    public function edit($id)
    {
        $item = ChuongTrinhGiamGia::findOrFail($id);
        return view('admin.giamgia.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = ChuongTrinhGiamGia::findOrFail($id);

        $item->update($request->all());

        return redirect()->route('admin.giamgia.index')
            ->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        ChuongTrinhGiamGia::destroy($id);
        return back()->with('success', 'Đã xóa!');
    }
}