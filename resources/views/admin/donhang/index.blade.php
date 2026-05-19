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
    @php
        $badgeClass = match($dh->trangThai) {

            'Hoàn thành' => 'success',

            'da_huy',
            'Đã hủy' => 'danger',

            'dang_xu_ly',
            'Đang xử lý' => 'primary',

            'cho_xac_nhan',
            'Cho_xac_nhan',
            'Chờ xác nhận' => 'warning',

            default => 'secondary'
        };

        $trangThaiText = match($dh->trangThai) {

            'da_huy' => 'Đã hủy',

            'cho_xac_nhan',
            'Cho_xac_nhan' => 'Chờ xác nhận',

            'dang_xu_ly' => 'Đang xử lý',

            default => $dh->trangThai
        };
    @endphp

    <span class="badge bg-{{ $badgeClass }} px-3 py-2 rounded-pill">
        {{ $trangThaiText }}
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