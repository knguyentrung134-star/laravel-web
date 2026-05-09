@extends('layouts.app')

@section('title', 'Lịch sử mua hàng')

@section('content')
<div class="container">
    <h2 class="mb-4">📦 Lịch sử mua hàng của bạn</h2>

    @if($donHangs->isEmpty())
        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
    @else
        @foreach($donHangs as $don)
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