@extends('layouts.app')

@section('title', 'Thêm sản phẩm')

@section('content')
<h2>Thêm sản phẩm mới</h2>

<form action="{{ route('admin.sanpham.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Tên sản phẩm</label>
        <input type="text" name="tenSanPham" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Mô tả</label>
        <textarea name="moTa" class="form-control" rows="3"></textarea>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label>Giá (₫)</label>
            <input type="number" name="gia" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label>Số lượng</label>
            <input type="number" name="soLuong" class="form-control" required>
        </div>
    </div>
    <div class="mb-3 mt-3">
        <label>Hình ảnh</label>
        <input type="file" name="hinh_anh" class="form-control" accept="image/*">
    </div>
    <div class="mb-3">
        <label>Trạng thái</label>
        <select name="trangThai" class="form-select">
            <option value="Còn hàng">Còn hàng</option>
            <option value="Hết hàng">Hết hàng</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
    <a href="{{ route('admin.sanpham.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection 