@extends('layouts.app')

@section('title', 'Quản lý Kho hàng')

@section('content')
<h2 class="mb-4">📦 Quản lý Kho hàng</h2>

<a href="{{ route('admin.kho.create') }}" class="btn btn-success mb-3">+ Nhập hàng mới</a>
<a href="{{ route('admin.kho.lichsu') }}" class="btn btn-info mb-3">📜 Xem lịch sử nhập hàng</a>    

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Sản phẩm</th>
            <th>Tồn kho</th>
            <th>Giá bán</th>
        </tr>
    </thead>
    <tbody>
        @foreach($hangTonKhos as $ht)
        <tr>
            <td>{{ $ht->sanPham->tenSanPham }}</td>
            <td class="fw-bold">{{ $ht->soLuong }}</td>
            <td>{{ number_format($ht->sanPham->gia) }} ₫</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $hangTonKhos->links() }}
@endsection