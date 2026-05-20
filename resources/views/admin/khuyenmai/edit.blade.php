@extends('layouts.app')

@section('title', 'Sửa Khuyến mãi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-warning text-white">
                <h4>Sửa Khuyến mãi #{{ $khuyenmai->idKhuyenMai ?? $khuyenMai->idKhuyenMai ?? 'N/A' }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.khuyenmai.update', $khuyenmai ?? $khuyenMai) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @php
                        $km = $khuyenmai ?? $khuyenMai ?? null;
                    @endphp

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã khuyến mãi <span class="text-danger">*</span></label>
                            <input type="text" name="maKhuyenMai" 
                                   class="form-control @error('maKhuyenMai') is-invalid @enderror" 
                                   value="{{ old('maKhuyenMai', $km->maKhuyenMai ?? '') }}" required>
                            @error('maKhuyenMai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên khuyến mãi</label>
                            <input type="text" name="tenKhuyenMai" 
                                   class="form-control @error('tenKhuyenMai') is-invalid @enderror" 
                                   value="{{ old('tenKhuyenMai', $km->tenKhuyenMai ?? '') }}">
                            @error('tenKhuyenMai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả khuyến mãi <span class="text-danger">*</span></label>
                        <input type="text" name="moTaKhuyenMai" 
                               class="form-control @error('moTaKhuyenMai') is-invalid @enderror" 
                               value="{{ old('moTaKhuyenMai', $km->moTaKhuyenMai ?? '') }}" required>
                        @error('moTaKhuyenMai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">% Giảm <span class="text-danger">*</span></label>
                            <input type="number" name="phanTramGiam" min="1" max="100"
                                   class="form-control @error('phanTramGiam') is-invalid @enderror" 
                                   value="{{ old('phanTramGiam', $km->phanTramGiam ?? '') }}" required>
                            @error('phanTramGiam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" name="ngayBatDau" 
                                   class="form-control @error('ngayBatDau') is-invalid @enderror" 
                                   value="{{ old('ngayBatDau', $km->ngayBatDau) }}" required>
                            @error('ngayBatDau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" name="ngayKetThuc" 
                                   class="form-control @error('ngayKetThuc') is-invalid @enderror" 
                                   value="{{ old('ngayKetThuc', $km->ngayKetThuc) }}" required>
                            @error('ngayKetThuc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="trangThai" class="form-select">
                            <option value="1" {{ old('trangThai', $km->trangThai ?? 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ old('trangThai', $km->trangThai ?? 1) == 0 ? 'selected' : '' }}>Tắt</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('admin.khuyenmai.index') }}" class="btn btn-secondary me-md-2">Hủy</a>
                        <button type="submit" class="btn btn-warning">Cập nhật Khuyến mãi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection