@extends('layouts.app')

@section('title', 'Lịch sử Nhập hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📜 Lịch sử Nhập hàng</h2>
    <a href="{{ route('admin.kho.create') }}" class="btn btn-success">+ Nhập hàng mới</a>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Mã phiếu</th>
            <th>Ngày nhập</th>
            <th>Trạng thái</th>
            <th>Chi tiết</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nhapHangs as $nhap)
        <tr>
            <td>#{{ $nhap->idNhapHang }}</td>
            <td>{{ $nhap->ngayNhap }}</td>
            <td>
                <span class="badge bg-success">{{ $nhap->trangThai }}</span>
            </td>
            <td>
                <ul class="list-unstyled mb-0">
                    @foreach($nhap->chiTietNhapHangs as $ct)
                    <li>
                        <strong>{{ $ct->sanPham->tenSanPham ?? 'N/A' }}</strong> 
                        × {{ $ct->soLuong }} 
                        ({{ number_format($ct->donGiaNhap) }} ₫)
                    </li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $nhapHangs->links() }}
@endsection