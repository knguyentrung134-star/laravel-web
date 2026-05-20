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

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã khuyến mãi <span class="text-danger">*</span></label>
                            <input type="text" name="maKhuyenMai" 
                                   class="form-control @error('maKhuyenMai') is-invalid @enderror" 
                                   value="{{ old('maKhuyenMai') }}" 
                                   placeholder="Ví dụ: GIAM50" required>
                            @error('maKhuyenMai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên khuyến mãi</label>
                            <input type="text" name="tenKhuyenMai" 
                                   class="form-control @error('tenKhuyenMai') is-invalid @enderror" 
                                   value="{{ old('tenKhuyenMai') }}">
                            @error('tenKhuyenMai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

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
                        <div class="col-md-4 mb-3">
                            <label class="form-label">% Giảm <span class="text-danger">*</span></label>
                            <input type="number" name="phanTramGiam" min="1" max="100"
                                   class="form-control @error('phanTramGiam') is-invalid @enderror" 
                                   value="{{ old('phanTramGiam') }}" required>
                            @error('phanTramGiam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" name="ngayBatDau" 
                                   class="form-control @error('ngayBatDau') is-invalid @enderror" 
                                   value="{{ old('ngayBatDau') }}" required>
                            @error('ngayBatDau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
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