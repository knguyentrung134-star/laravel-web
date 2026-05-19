<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SanPhamController extends Controller
{
public function index()
{
    $sanPhams = SanPham::orderBy('idSanPham', 'desc')->paginate(10);
    
    return view('admin.sanpham.index', compact('sanPhams'));
}

    public function create()
    {
        return view('admin.sanpham.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenSanPham' => 'required|string|max:100',
            'moTa'       => 'nullable|string',
            'theLoai'    => 'nullable|string|max:100',
            'gia'        => 'required|numeric|min:0',
            'soLuong'    => 'required|integer|min:0',
            'hinh_anh'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trangThai'  => 'required|in:Còn hàng,Hết hàng',
        ]);

        $data = $request->only([
            'tenSanPham', 'moTa', 'theLoai', 'gia', 'soLuong', 'trangThai'
        ]);

        $data['trangThai'] = $data['soLuong'] > 0 ? 'Còn hàng' : 'Hết hàng';

        if ($request->hasFile('hinh_anh')) {
            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) 
                        . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['hinh_anh'] = $imageName;
        }

        SanPham::create($data);

        return redirect()->route('admin.sanpham.index')
            ->with('success', '✅ Thêm sản phẩm thành công!');
    }

    public function edit(SanPham $sanpham)
    {
        return view('admin.sanpham.edit', compact('sanpham'));
    }

    public function update(Request $request, SanPham $sanpham)
    {
        $request->validate([
            'tenSanPham' => 'required|string|max:100',
            'moTa'       => 'nullable|string',
            'theLoai'    => 'nullable|string|max:100',
            'gia'        => 'required|numeric|min:0',
            'soLuong'    => 'required|integer|min:0',
            'hinh_anh'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trangThai'  => 'required|in:Còn hàng,Hết hàng',
        ]);

        $data = $request->only(['tenSanPham', 'moTa', 'theLoai', 'gia', 'soLuong']);

        $data['trangThai'] = $data['soLuong'] > 0 ? 'Còn hàng' : 'Hết hàng';

        if ($request->hasFile('hinh_anh')) {
            if ($sanpham->hinh_anh && File::exists(public_path('images/' . $sanpham->hinh_anh))) {
                File::delete(public_path('images/' . $sanpham->hinh_anh));
            }

            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) 
                        . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['hinh_anh'] = $imageName;
        }

        $sanpham->update($data);

        return redirect()->route('admin.sanpham.index')
            ->with('success', '✅ Cập nhật sản phẩm thành công!');
    }
    public function destroy(SanPham $sanpham)
{
    try {

        // Xóa ảnh
        if ($sanpham->hinh_anh && File::exists(public_path('images/' . $sanpham->hinh_anh))) {
            File::delete(public_path('images/' . $sanpham->hinh_anh));
        }

        // Xóa sản phẩm
        $sanpham->delete();

        return redirect()->route('admin.sanpham.index')
            ->with('success', "✅ Đã xóa sản phẩm!");

    } catch (\Exception $e) {

        return redirect()->back()
            ->with('error', 'Lỗi khi xóa: ' . $e->getMessage());
    }
}
}