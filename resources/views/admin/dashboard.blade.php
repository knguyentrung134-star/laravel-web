@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">

    <!-- HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-6">
                👋 Xin chào, <strong>{{ auth()->user()->tenNguoiDung }}</strong>!
            </h1>
            <p class="lead text-muted">
                Chào mừng bạn đến với trang quản trị hệ thống Băng Đĩa Nhạc
            </p>
        </div>
    </div>

    <!-- GRID CARD -->
    <div class="row g-4">

        <!-- SẢN PHẨM -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <i class="fas fa-box fa-3x text-primary mb-3"></i>
                <h5>Quản lý Sản phẩm</h5>
                <p class="text-muted">Thêm, sửa, xóa băng đĩa</p>
                <a href="{{ route('admin.sanpham.index') }}" class="btn btn-primary w-100">
                    Vào quản lý
                </a>
            </div>
        </div>

        <!-- ĐƠN HÀNG -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <i class="fas fa-shopping-cart fa-3x text-success mb-3"></i>
                <h5>Quản lý Đơn hàng</h5>
                <p class="text-muted">Theo dõi & cập nhật trạng thái</p>
                <a href="{{ route('admin.donhang.index') }}" class="btn btn-success w-100">
                    Vào quản lý
                </a>
            </div>
        </div>

        <!-- KHO -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <i class="fas fa-warehouse fa-3x text-warning mb-3"></i>
                <h5>Quản lý Kho</h5>
                <p class="text-muted">Kiểm soát tồn kho</p>
                <a href="{{ route('admin.kho.index') }}" class="btn btn-warning w-100">
                    Vào quản lý
                </a>
            </div>
        </div>

        <!-- 🔥 KHÁCH HÀNG (MỚI) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <i class="fas fa-users fa-3x text-danger mb-3"></i>
                <h5>Quản lý Khách hàng</h5>
                <p class="text-muted">Xem & quản lý tài khoản</p>
                <a href="{{ route('admin.khachhang.index') }}" class="btn btn-danger w-100">
                    Vào quản lý
                </a>
            </div>
        </div>
        <div class="col-md-4">
    <div class="card h-100 shadow-sm border-0 text-center p-4">
        <i class="fas fa-percent fa-3x text-warning mb-3"></i>
        <h5>Chương trình giảm giá</h5>
        <p class="text-muted">Theo mùa / thể loại</p>

        <a href="{{ route('admin.giamgia.index') }}" class="btn btn-warning w-100">
            Vào quản lý
        </a>
    </div>
</div>

        <!-- KHUYẾN MÃI -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <i class="fas fa-tags fa-3x text-info mb-3"></i>
                <h5>Quản lý Khuyến mãi</h5>
                <p class="text-muted">Tạo & quản lý ưu đãi</p>
                <a href="{{ route('admin.khuyenmai.index') }}" class="btn btn-info w-100">
                    Vào quản lý
                </a>
            </div>
        </div>

    </div>

    <!-- BACK -->
    <div class="mt-4">
        <a href="/" class="btn btn-outline-secondary">
            ← Về Trang chủ
        </a>
    </div>

</div>
@endsection