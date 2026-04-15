@extends('layouts.app')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<h2 class="mb-4">📋 Quản lý Đơn hàng</h2>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Mã ĐH</th>
            <th>Khách hàng</th>
            <th>Ngày lập</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($donHangs as $dh)
        <tr>
            <td>#{{ $dh->idDonHang }}</td>
            <td>{{ $dh->khachHang?->tenKhachHang ?? 'N/A' }}</td>
            <td>{{ $dh->ngayLap }}</td>
            <td class="text-danger fw-bold">{{ number_format($dh->tongThanhTien) }} ₫</td>
            <td>
                <span class="badge bg-{{ $dh->trangThai == 'Hoàn thành' ? 'success' : ($dh->trangThai == 'Đang xử lý' ? 'warning' : 'info') }}">
                    {{ $dh->trangThai }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.donhang.show', $dh) }}" class="btn btn-sm btn-info">Chi tiết</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $donHangs->links() }}
@endsection