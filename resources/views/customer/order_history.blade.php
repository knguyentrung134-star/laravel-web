@extends('layouts.app')

@section('title', 'Lịch sử mua hàng')

@section('content')
<div class="container">
    <h2 class="mb-4">📦 Lịch sử mua hàng của bạn</h2>

    @if($donHangs->isEmpty())
        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
    @else
        @foreach($donHangs as $don)
        @php
            $giamGia = $don->giamGia ?? 0;
            $tongGoc = $don->tongThanhTien + $giamGia;
        @endphp

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Mã đơn hàng: #{{ $don->idDonHang }}</strong>
                <div>
                    <small class="text-muted">Ngày: {{ $don->ngayLap }}</small>
                    <span class="badge bg-{{ $don->trangThai == 'Hoàn thành' ? 'success' : 'warning' }} ms-2">
                        {{ $don->trangThai }}
                    </span>
                </div>
            </div>

            <div class="card-body">
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
                        @foreach($don->chiTietDonHangs as $ct)
                        @php
                            $tyLeGiam = $tongGoc > 0 ? ($giamGia / $tongGoc) : 0;
                            $thanhTienSauKM = round($ct->soLuong * $ct->donGia * (1 - $tyLeGiam));
                        @endphp
                        <tr>
                            <td>{{ $ct->sanPham->tenSanPham }}</td>
                            <td>{{ $ct->soLuong }}</td>
                            <td>{{ number_format($ct->donGia) }} ₫</td>
                            <td class="fw-bold text-danger">
                                {{ number_format($thanhTienSauKM) }} ₫
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-light text-end">
                <strong>Tổng thanh toán (sau khuyến mãi):</strong> 
                <span class="fs-5 text-danger">{{ number_format($don->tongThanhTien) }} ₫</span>
            </div>
        </div>
        @endforeach

        {{ $donHangs->links() }}
    @endif
</div>
@endsection