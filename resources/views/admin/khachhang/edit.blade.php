@extends('layouts.app')

@section('title', 'Chỉnh sửa Khách hàng')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Chỉnh sửa thông tin khách hàng #{{ $kh->idNguoiDung }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.khachhang.update', $kh->idNguoiDung) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên người dùng <span class="text-danger">*</span></label>
                        <input type="text" name="tenNguoiDung" class="form-control" 
                               value="{{ old('tenNguoiDung', $kh->tenNguoiDung ?? '') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" 
                               value="{{ old('email', $kh->email ?? '') }}" required>
                    </div>
                </div>

                <!-- Bạn có thể thêm các field khác sau này: sdt, diaChi, ... -->

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                    <a href="{{ route('admin.khachhang.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection