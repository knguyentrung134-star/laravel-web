@extends('layouts.app')

@section('title', 'Chi tiết Đơn hàng #{{ $donhang->idDonHang }}')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h3>Chi tiết Đơn hàng #{{ $donhang->idDonHang }}</h3>
        
        <table class="table table-bordered">
            <thead>
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
                    <td>{{ $ct->sanPham->tenSanPham }}</td>
                    <td>{{ $ct->soLuong }}</td>
                    <td>{{ number_format($ct->donGia) }} ₫</td>
                    <td>{{ number_format($ct->soLuong * $ct->donGia) }} ₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                Thông tin khách hàng
            </div>
            <div class="card-body">
                <p><strong>Tên:</strong> {{ $donhang->khachHang->tenKhachHang }}</p>
                <p><strong>Địa chỉ:</strong> {{ $donhang->khachHang->diaChi }}</p>
                <p><strong>SĐT:</strong> {{ $donhang->khachHang->soDienThoai }}</p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Cập nhật trạng thái</div>
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

        <a href="{{ route('admin.donhang.index') }}" class="btn btn-secondary">← Quay lại danh sách</a>
    </div>
</div>
@endsection