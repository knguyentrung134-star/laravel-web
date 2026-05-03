@extends('layouts.app')

@section('title', 'Thêm chương trình giảm giá')

@section('content')
<div class="container">
    <h3 class="mb-4">➕ Thêm chương trình giảm giá</h3>

    <form action="{{ route('admin.giamgia.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Tên chương trình</label>
            <input type="text" name="tenChuongTrinh" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Thể loại</label>
            <input type="text" name="theLoai" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>% Giảm</label>
            <input type="number" name="phanTramGiam" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ngày bắt đầu</label>
            <input type="date" name="ngayBatDau" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ngày kết thúc</label>
            <input type="date" name="ngayKetThuc" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.giamgia.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection