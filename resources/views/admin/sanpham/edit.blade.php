@extends('layouts.app')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="container">
    <h2 class="mb-4">Sửa sản phẩm: {{ $sanpham->tenSanPham }}</h2>
    
    <form action="{{ route('admin.sanpham.update', $sanpham) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="tenSanPham" 
                   class="form-control" 
                   value="{{ old('tenSanPham', $sanpham->tenSanPham) }}" required>
        </div>

        <!-- Trường Thể loại -->
        <div class="mb-3">
            <label class="form-label">Thể loại</label>
            <input type="text" name="theLoai" class="form-control" 
                   value="{{ old('theLoai', $sanpham->theLoai) }}" 
                   placeholder="Ví dụ: Pop, Nhạc Trẻ, Bolero, Rap, Rock...">
            <small class="text-muted">Nhập thể loại nhạc (có thể để trống)</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="moTa" class="form-control" rows="4">{{ old('moTa', $sanpham->moTa) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Giá (₫) <span class="text-danger">*</span></label>
                <input type="number" name="gia" 
                       class="form-control" 
                       value="{{ old('gia', $sanpham->gia) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                <input type="number" name="soLuong" 
                       class="form-control" 
                       value="{{ old('soLuong', $sanpham->soLuong) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh hiện tại</label><br>
            @if($sanpham->hinh_anh)
                <img src="{{ asset('images/' . $sanpham->hinh_anh) }}" 
                     alt="{{ $sanpham->tenSanPham }}" 
                     class="img-thumbnail mb-2" style="max-height: 150px;">
            @else
                <p class="text-muted">Chưa có hình ảnh</p>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Thay đổi hình ảnh mới (nếu có)</label>
            <input type="file" name="hinh_anh" 
                   class="form-control" accept="image/*">
            <small class="text-muted">Để trống nếu không muốn thay đổi hình ảnh</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
            <select name="trangThai" class="form-select">
                <option value="Còn hàng" {{ old('trangThai', $sanpham->trangThai) == 'Còn hàng' ? 'selected' : '' }}>Còn hàng</option>
                <option value="Hết hàng" {{ old('trangThai', $sanpham->trangThai) == 'Hết hàng' ? 'selected' : '' }}>Hết hàng</option>
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
            <a href="{{ route('admin.sanpham.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection