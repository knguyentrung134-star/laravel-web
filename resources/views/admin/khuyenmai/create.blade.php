@extends('layouts.app')

@section('title', 'Thêm Khuyến mãi mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4>🎟️ Thêm Khuyến mãi mới</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.khuyenmai.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Mô tả khuyến mãi <span class="text-danger">*</span></label>
                        <input type="text" name="moTaKhuyenMai" 
                               class="form-control @error('moTaKhuyenMai') is-invalid @enderror" 
                               value="{{ old('moTaKhuyenMai') }}" required>
                        @error('moTaKhuyenMai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" name="ngayBatDau" 
                                   class="form-control @error('ngayBatDau') is-invalid @enderror" 
                                   value="{{ old('ngayBatDau') }}" required>
                            @error('ngayBatDau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" name="ngayKetThuc" 
                                   class="form-control @error('ngayKetThuc') is-invalid @enderror" 
                                   value="{{ old('ngayKetThuc') }}" required>
                            @error('ngayKetThuc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('admin.khuyenmai.index') }}" class="btn btn-secondary me-md-2">Hủy</a>
                        <button type="submit" class="btn btn-success">Lưu khuyến mãi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection