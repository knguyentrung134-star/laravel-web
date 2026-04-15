@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<div class="row mb-5">
    <div class="col-12 text-center">
        <h1 class="display-5 fw-bold">🎵 Danh sách Băng Đĩa Nhạc</h1>
        <p class="lead text-muted">Bộ sưu tập băng đĩa nhạc chất lượng cao</p>
    </div>
</div>

<div class="row g-4">
    @foreach($sanPhams as $sp)
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card h-100 shadow-sm border-0 product-card">
            <div style="height: 240px; overflow: hidden;">
                @if($sp->hinh_anh)
                    <img src="{{ asset('images/' . $sp->hinh_anh) }}" 
                         class="card-img-top" 
                         style="width:100%; height:100%; object-fit: cover;" 
                         alt="{{ $sp->tenSanPham }}">
                @else
                    <div class="bg-light h-100 d-flex align-items-center justify-content-center">
                        <h1 class="text-secondary">🎵</h1>
                    </div>
                @endif
            </div>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $sp->tenSanPham }}</h5>
                <p class="text-danger fw-bold fs-5">{{ number_format($sp->gia) }} ₫</p>
                
                <p class="text-muted small flex-grow-1">
                    {{ $sp->moTa ? substr($sp->moTa, 0, 70) . '...' : 'Không có mô tả' }}
                </p>

                <div class="mt-auto">
                    <a href="{{ route('sanpham.show', $sp) }}" class="btn btn-outline-primary w-100 mb-2">
                        Xem chi tiết
                    </a>
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="idSanPham" value="{{ $sp->idSanPham }}">
                        <button type="submit" class="btn btn-primary w-100">Thêm vào giỏ hàng</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection