@extends('layouts.app')

@section('title', 'Chi tiết Đơn hàng #{{ $donhang->idDonHang }}')

@section('content')
<div class="row">
    <!-- Bên trái: Chi tiết đơn hàng -->
    <div class="col-md-8">
        <h3>Chi tiết Đơn hàng #{{ $donhang->idDonHang }}</h3>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donhang->chiTietDonHangs as $ct)
                <tr>
                    <td>{{ $ct->sanPham->tenSanPham ?? 'N/A' }}</td>
                    <td>{{ $ct->soLuong }}</td>
                    <td class="text-end">
                        @if($ct->sanPham && $ct->donGia < $ct->sanPham->gia)
                            <span class="text-danger fw-bold">{{ number_format($ct->donGia) }} ₫</span>
                            <br>
                            <small class="text-muted text-decoration-line-through">
                                {{ number_format($ct->sanPham->gia) }} ₫
                            </small>
                        @else
                            {{ number_format($ct->donGia) }} ₫
                        @endif
                    </td>
                    <td class="fw-bold text-danger text-end">
                        {{ number_format($ct->soLuong * $ct->donGia) }} ₫
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Tổng thanh toán:</td>
                    <td class="text-danger">{{ number_format($donhang->tongThanhTien) }} ₫</td>
                </tr>
                @if($donhang->giamGia > 0)
                <tr class="text-success fw-bold">
                    <td colspan="3" class="text-end">Giảm giá:</td>
                    <td>- {{ number_format($donhang->giamGia) }} ₫</td>
                </tr>
                @endif
            </tfoot>
        </table>
    </div>

    <!-- Bên phải: Thông tin khách hàng + Cập nhật trạng thái -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Thông tin khách hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Tên:</strong> {{ $donhang->khachHang->tenKhachHang ?? 'N/A' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $donhang->khachHang->diaChi ?? 'N/A' }}</p>
                <p><strong>SĐT:</strong> {{ $donhang->khachHang->soDienThoai ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Cập nhật trạng thái đơn hàng -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Cập nhật trạng thái</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.donhang.update', $donhang) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="trangThai" class="form-select mb-3">
                        <option value="Đang xử lý" {{ $donhang->trangThai == 'Đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="Đã xác nhận" {{ $donhang->trangThai == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="Đang giao" {{ $donhang->trangThai == 'Đang giao' ? 'selected' : '' }}>Đang giao</option>
                        <option value="Hoàn thành" {{ $donhang->trangThai == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="Đã hủy" {{ $donhang->trangThai == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                    <button type="submit" class="btn btn-success w-100">Cập nhật trạng thái</button>
                </form>
            </div>
        </div>

        <a href="{{ route('admin.donhang.index') }}" class="btn btn-secondary w-100">
            ← Quay lại danh sách đơn hàng
        </a>
    </div>
</div>
@endsection