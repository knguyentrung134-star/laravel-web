@extends('layouts.app')

@section('title', $sanPham->tenSanPham)

@section('content')
<div class="row">
    <!-- Hình ảnh -->
    <div class="col-md-5">
        @if($sanPham->hinh_anh)
            <img src="{{ asset('images/' . $sanPham->hinh_anh) }}" 
                 class="img-fluid rounded shadow-sm w-100" 
                 alt="{{ $sanPham->tenSanPham }}">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px; border-radius: 10px;">
                <h1 class="text-secondary">🎵</h1>
            </div>
        @endif
    </div>

    <!-- Thông tin sản phẩm -->
    <div class="col-md-7">
        <h2>{{ $sanPham->tenSanPham }}</h2>

        <!-- ✅ GIÁ SẢN PHẨM -->
        <div class="mb-4">
            @if(isset($giamGia))
                <!-- Giá gốc -->
                <span class="text-muted text-decoration-line-through fs-5">
                    {{ number_format($sanPham->gia) }} ₫
                </span>

                <!-- % giảm -->
                <span class="badge bg-danger ms-2">
                    -{{ $giamGia->phanTramGiam }}%
                </span>

                <!-- Giá sau giảm -->
                <h3 class="text-danger fw-bold mt-2">
                    {{ number_format($giaSauGiam) }} ₫
                </h3>
            @else
                <!-- Không giảm -->
                <h3 class="text-danger fw-bold">
                    {{ number_format($sanPham->gia) }} ₫
                </h3>
            @endif
        </div>

        @php
            $avg = round($sanPham->danhGias->avg('soSao'), 1);
        @endphp

        <p>⭐ Trung bình: <strong>{{ $avg ?? 0 }}</strong> / 5</p>

        <p class="mb-4">
            {{ $sanPham->moTa ?? 'Không có mô tả cho sản phẩm này.' }}
        </p>

        <div class="mb-4">
            <strong>Trạng thái:</strong>
            <span class="badge bg-{{ $sanPham->trangThai === 'Còn hàng' ? 'success' : 'danger' }} ms-2">
                {{ $sanPham->trangThai }}
            </span>
        </div>

        <!-- Thêm vào giỏ hàng -->
        @if($sanPham->trangThai === 'Còn hàng')
        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="idSanPham" value="{{ $sanPham->idSanPham }}">
            <button type="submit" class="btn btn-success btn-lg px-5">
                🛒 Thêm vào giỏ hàng
            </button>
        </form>
        @else
            <button class="btn btn-secondary btn-lg px-5" disabled>Hết hàng</button>
        @endif

        <!-- Đánh giá -->
        <div class="card mt-5">
            <div class="card-header bg-primary text-white">
                <h5>Đánh giá sản phẩm</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('danhgia.store', $sanPham) }}" method="POST">
                    @csrf
                    <select name="soSao" class="form-select mb-3" required>
                        <option value="">-- Chọn số sao --</option>
                        @for($i=5; $i>=1; $i--)
                            <option value="{{ $i }}">{{ $i }} ⭐</option>
                        @endfor
                    </select>

                    <textarea name="noiDung" class="form-control mb-3" rows="4" 
                              placeholder="Nhập đánh giá của bạn..." required></textarea>

                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                </form>
            </div>
        </div>

        <!-- Danh sách đánh giá -->
        <div class="mt-5">
            <h5>Đánh giá từ khách hàng ({{ $sanPham->danhGias->count() }})</h5>

            @forelse($sanPham->danhGias as $danhgia)
            <div class="border p-3 mb-3 rounded">
                <div class="d-flex justify-content-between">
                    <strong>{{ $danhgia->nguoiDung->tenNguoiDung ?? 'Khách hàng' }}</strong>
                    <div>
                        @for($i=1; $i<=5; $i++)
                            <span class="{{ $i <= $danhgia->soSao ? 'text-warning' : 'text-secondary' }}">★</span>
                        @endfor
                    </div>
                </div>

                <p class="mt-2 mb-1">{{ $danhgia->noiDung }}</p>

                <small class="text-muted">
                    {{ optional($danhgia->created_at)->format('d/m/Y H:i') }}
                </small>
            </div>
            @empty
                <div class="alert alert-info">Chưa có đánh giá nào.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection