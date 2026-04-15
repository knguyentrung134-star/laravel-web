@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-6">
                👋 Xin chào, <strong>{{ auth()->user()->tenNguoiDung }}</strong>!
            </h1>
            <p class="lead text-muted">Chào mừng bạn đến với trang quản trị hệ thống Băng Đĩa Nhạc</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Card Sản phẩm -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="fas fa-box fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Quản lý Sản phẩm</h5>
                    <p class="card-text text-muted">Thêm, sửa, xóa danh sách băng đĩa</p>
                    <a href="{{ route('admin.sanpham.index') }}" class="btn btn-primary btn-lg w-100">
                        Vào quản lý sản phẩm
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Đơn hàng -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Quản lý Đơn hàng</h5>
                    <p class="card-text text-muted">Xem danh sách đơn hàng, cập nhật trạng thái</p>
                    <a href="{{ route('admin.donhang.index') }}" class="btn btn-success btn-lg w-100">
                        Vào quản lý đơn hàng
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Kho hàng -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="fas fa-warehouse fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Quản lý Kho hàng</h5>
                    <p class="card-text text-muted">Theo dõi tồn kho, nhập hàng mới</p>
                    <a href="{{ route('admin.kho.index') }}" class="btn btn-warning btn-lg w-100">
                        Vào quản lý kho hàng
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="/" class="btn btn-outline-secondary">← Về Trang chủ</a>
    </div>
    <!-- Card Khuyến mãi -->
    <div class="col-md-4">
    <div class="card h-100 shadow-sm border-0">
        <div class="card-body text-center py-5">
            <i class="fas fa-tags fa-3x text-info mb-3"></i>
            <h5 class="card-title">Quản lý Khuyến mãi</h5>
            <p class="card-text text-muted">Tạo và quản lý các chương trình khuyến mãi</p>
            <a href="{{ route('admin.khuyenmai.index') }}" class="btn btn-info btn-lg w-100">
                Vào quản lý khuyến mãi
            </a>
        </div>
    </div>
</div>
</div>
@endsection 