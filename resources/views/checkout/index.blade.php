@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h3>Thông tin đơn hàng</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->sanPham->tenSanPham }}</td>
                    <td>{{ $item->soLuong }}</td>
                    <td>{{ number_format($item->sanPham->gia) }} ₫</td>
                    <td>{{ number_format($item->soLuong * $item->sanPham->gia) }} ₫</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Tổng tiền:</td>
                    <td class="text-danger">{{ number_format($total) }} ₫</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Thông tin khách hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Tên:</strong> {{ $khachHang->tenKhachHang }}</p>
                <p><strong>Địa chỉ:</strong> {{ $khachHang->diaChi }}</p>
                <p><strong>SĐT:</strong> {{ $khachHang->soDienThoai }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Phương thức thanh toán</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="phuongThuc" class="form-select" required>
                            <option value="ChuyenKhoan">Chuyển khoản</option>
                            <option value="TienMat">Tiền mặt</option>
                            <option value="TheTinDung">Thẻ tín dụng</option>
                            <option value="ViDienTu">Ví điện tử</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Xác nhận đặt hàng</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection