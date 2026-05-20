<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;

class KhuyenMaiController extends Controller
{
public function index()
{
    $khuyenMais = KhuyenMai::orderBy('idKhuyenMai', 'desc')
                           ->paginate(10);   // ← Thay get() thành paginate()

    return view('admin.khuyenmai.index', compact('khuyenMais'));
}
public function create()
{
    return view('admin.khuyenmai.create');
}

public function store(Request $request)
{
    $request->validate([
        'maKhuyenMai'     => 'required|string|unique:khuyenmai,maKhuyenMai',
        'tenKhuyenMai'    => 'nullable|string|max:100',
        'moTaKhuyenMai'   => 'required|string',
        'phanTramGiam'    => 'required|integer|min:1|max:100',
        'ngayBatDau'      => 'required|date',
        'ngayKetThuc'     => 'required|date|after_or_equal:ngayBatDau',
    ]);

    KhuyenMai::create($request->all());

    return redirect()->route('admin.khuyenmai.index')
        ->with('success', 'Thêm khuyến mãi thành công!');
}

public function edit($id)
{
    $khuyenMai = KhuyenMai::findOrFail($id);
    return view('admin.khuyenmai.edit', compact('khuyenMai'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'maKhuyenMai'     => 'required|string|unique:khuyenmai,maKhuyenMai,'.$id.',idKhuyenMai',
        'tenKhuyenMai'    => 'nullable|string|max:100',
        'moTaKhuyenMai'   => 'required|string',
        'phanTramGiam'    => 'required|integer|min:1|max:100',
        'ngayBatDau'      => 'required|date',
        'ngayKetThuc'     => 'required|date|after_or_equal:ngayBatDau',
        'trangThai'       => 'required|in:0,1',
    ]);

    $khuyenMai = KhuyenMai::findOrFail($id);
    $khuyenMai->update($request->all());

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