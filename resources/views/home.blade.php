@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<div class="container mt-4">

    <!-- 🎧 BANNER -->
    <div id="bannerCarousel" 
         class="carousel slide carousel-fade mb-5"
         data-bs-ride="carousel"
         data-bs-interval="4000"
         data-bs-pause="false">

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner rounded-4 overflow-hidden">

            <div class="carousel-item active">
                <div class="hero-slide" style="background-image: url('{{ asset('images/back.jpg') }}')">
                    <div class="overlay"></div>
                    <div class="content">
                        <h1 class="fw-bold">🎵 Nguyễn Trung Kiên</h1>
                        <p>Nghe nhạc - Sưu tầm - Đam mê</p>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="hero-slide" style="background-image: url('{{ asset('images/back2.jpg') }}')">
                    <div class="overlay"></div>
                    <div class="content">
                        <h1>💿 Album 9x Bất Hủ</h1>
                        <p>Giai điệu không bao giờ lỗi thời</p>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="hero-slide" style="background-image: url('{{ asset('images/back3.jpg') }}')">
                    <div class="overlay"></div>
                    <div class="content">
                        <h1>📼 Cassette Hoài Niệm</h1>
                        <p>Trở về ký ức tuổi thơ</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 🔥 TITLE -->
    <h3 class="mb-4">🔥 Album nổi bật</h3>

    <!-- 🎵 PRODUCTS -->
    <div class="row">
        @foreach($sanPhams as $sp)
            {{-- Chỉ hiển thị sản phẩm đang kinh doanh --}}
            @if($sp->trangThai != 'Ngừng kinh doanh' && $sp->trangThai != null)
            <div class="col-md-3 mb-4">
                <div class="card product-card p-3 h-100 position-relative">

                    {{-- 🔥 RIBBON SALE --}}
                    @if(isset($sp->phanTramGiam) && $sp->phanTramGiam > 0)
                    <div class="sale-ribbon">
                        🔥 -{{ $sp->phanTramGiam }}%
                    </div>
                    @endif

                    <img src="{{ asset('images/' . $sp->hinh_anh) }}" 
                         class="card-img-top mb-3"
                         style="height:200px; object-fit:cover;">

                    <h6 class="product-title">{{ $sp->tenSanPham }}</h6>

                    <p class="text-muted small">
                        {{ $sp->theLoai }}
                    </p>

                    {{-- 💰 GIÁ --}}
                    @if(isset($sp->phanTramGiam) && $sp->phanTramGiam > 0)
                        <p class="fw-bold">
                            <span class="text-danger fs-5">
                                {{ number_format($sp->gia) }} ₫
                            </span>
                            <br>
                            <small class="text-muted text-decoration-line-through">
                                {{ number_format($sp->gia_goc ?? $sp->gia) }} ₫
                            </small>
                        </p>
                    @else
                        <p class="text-success fw-bold">
                            {{ number_format($sp->gia) }} ₫
                        </p>
                    @endif

                    <a href="{{ route('sanpham.show', $sp->idSanPham) }}" 
                       class="btn btn-detail w-100 mb-2">
                        👁 Xem chi tiết
                    </a>

                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="idSanPham" value="{{ $sp->idSanPham }}">

                        <button class="btn btn-spotify w-100">
                            🛒 Thêm vào giỏ hàng
                        </button>
                    </form>

                </div>
            </div>
            @endif
        @endforeach
    </div>
    
</div>
@endsection