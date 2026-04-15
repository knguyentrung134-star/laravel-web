<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSanPhamRequest;
use App\Http\Requests\UpdateSanPhamRequest;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function store(StoreSanPhamRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('hinh_anh')) {
            $imageName = time() . '_' . $request->file('hinh_anh')->getClientOriginalName();
            $request->file('hinh_anh')->move(public_path('images'), $imageName);
            $data['hinh_anh'] = $imageName;
        }

        SanPham::create($data);

        return redirect()->route('admin.sanpham.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(SanPham $sanpham)
    {
        return view('admin.sanpham.edit', compact('sanpham'));
    }

    public function update(UpdateSanPhamRequest $request, SanPham $sanpham)
    {
        $data = $request->validated();

        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu có
            if ($sanpham->hinh_anh && file_exists(public_path('images/' . $sanpham->hinh_anh))) {
                unlink(public_path('images/' . $sanpham->hinh_anh));
            }

            $imageName = time() . '_' . $request->file('hinh_anh')->getClientOriginalName();
            $request->file('hinh_anh')->move(public_path('images'), $imageName);
            $data['hinh_anh'] = $imageName;
        }

        $sanpham->update($data);

        return redirect()->route('admin.sanpham.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

   public function destroy(SanPham $sanpham)
{
    // Kiểm tra xem sản phẩm có đang tồn tại trong đơn hàng nào không
    $coTrongDonHang = \App\Models\ChiTietDonHang::where('idSanPham', $sanpham->idSanPham)->exists();

    // Kiểm tra xem sản phẩm có trong kho không
    $coTrongKho = \App\Models\HangTonKho::where('idSanPham', $sanpham->idSanPham)->exists();

    if ($coTrongDonHang || $coTrongKho) {
        return redirect()->route('admin.sanpham.index')
            ->with('error', "Không thể xóa sản phẩm '{$sanpham->tenSanPham}' vì đang có dữ liệu liên quan (đơn hàng hoặc tồn kho)!");
    }

    // Nếu không có ràng buộc thì mới cho xóa
    if ($sanpham->hinh_anh && file_exists(public_path('images/' . $sanpham->hinh_anh))) {
        unlink(public_path('images/' . $sanpham->hinh_anh));
    }

    $sanpham->delete();

    return redirect()->route('admin.sanpham.index')
        ->with('success', 'Xóa sản phẩm thành công!');
}
}   