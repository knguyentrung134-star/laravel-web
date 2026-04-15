@extends('layouts.app')

@section('title', 'Lịch sử mua hàng')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>📦 Lịch sử mua hàng của bạn</h2>

        @if($donHangs->isEmpty())
            <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
        @else
            @foreach($donHangs as $don)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <strong>Mã đơn hàng:</strong> #{{ $don->idDonHang }} 
                    <span class="float-end">
                        Ngày: {{ $don->ngayLap }} 
                        | Trạng thái: <span class="badge bg-{{ $don->trangThai == 'Hoàn thành' ? 'success' : 'warning' }}">{{ $don->trangThai }}</span>
                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
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
                                <td>{{ $ct->sanPham->tenSanPham }}</td>
                                <td>{{ $ct->soLuong }}</td>
                                <td>{{ number_format($ct->donGia) }} ₫</td>
                                <td>{{ number_format($ct->soLuong * $ct->donGia) }} ₫</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-end fw-bold">
                        Tổng tiền: {{ number_format($don->tongThanhTien) }} ₫
                    </div>
                </div>
            </div>
            @endforeach

            {{ $donHangs->links() }}
        @endif
    </div>
</div>
@endsection