<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SanPhamController extends Controller
{
    public function index()
    {
        $sanPhams = SanPham::paginate(10);
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

        // Upload ảnh
        if ($request->hasFile('hinh_anh')) {
            $imageName = time() . '_' . $request->file('hinh_anh')->getClientOriginalName();
            $request->file('hinh_anh')->move(public_path('images'), $imageName);
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

        $data = $request->only([
            'tenSanPham', 'moTa', 'theLoai', 'gia', 'soLuong', 'trangThai'
        ]);

        // Upload ảnh mới
        if ($request->hasFile('hinh_anh')) {

            // Xóa ảnh cũ nếu tồn tại
            if ($sanpham->hinh_anh && File::exists(public_path('images/' . $sanpham->hinh_anh))) {
                File::delete(public_path('images/' . $sanpham->hinh_anh));
            }

            $imageName = time() . '_' . $request->file('hinh_anh')->getClientOriginalName();
            $request->file('hinh_anh')->move(public_path('images'), $imageName);
            $data['hinh_anh'] = $imageName;
        }

        $sanpham->update($data);

        return redirect()->route('admin.sanpham.index')
            ->with('success', '✅ Cập nhật sản phẩm thành công!');
    }

    public function destroy(SanPham $sanpham)
    {
        try {
            // ❗ Chỉ chặn nếu có trong đơn hàng
            $coTrongDonHang = \App\Models\ChiTietDonHang::where('idSanPham', $sanpham->idSanPham)->exists();

            if ($coTrongDonHang) {
                return redirect()->route('admin.sanpham.index')
                    ->with('error', "❌ Không thể xóa '{$sanpham->tenSanPham}' vì đã có trong đơn hàng!");
            }

            // ✅ Xóa tồn kho trước
            \App\Models\HangTonKho::where('idSanPham', $sanpham->idSanPham)->delete();

            // ✅ Xóa ảnh nếu có
            if ($sanpham->hinh_anh && File::exists(public_path('images/' . $sanpham->hinh_anh))) {
                File::delete(public_path('images/' . $sanpham->hinh_anh));
            }

            // ✅ Xóa sản phẩm
            $sanpham->delete();

            return redirect()->route('admin.sanpham.index')
                ->with('success', '✅ Xóa sản phẩm thành công!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.sanpham.index')
                ->with('error', '❌ Lỗi hệ thống: ' . $e->getMessage());
        }
    }
}