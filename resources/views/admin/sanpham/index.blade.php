@extends('layouts.app')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý Sản phẩm</h2>
    <a href="{{ route('admin.sanpham.create') }}" class="btn btn-success">+ Thêm sản phẩm mới</a>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Hình</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sanPhams as $sp)
        <tr>
            <td>{{ $sp->idSanPham }}</td>
            <td>
                @if($sp->hinh_anh)
                    <img src="{{ asset('images/' . $sp->hinh_anh) }}" width="60" alt="">
                @endif
            </td>
            <td>{{ $sp->tenSanPham }}</td>
            <td>{{ number_format($sp->gia) }} ₫</td>
            <td>{{ $sp->soLuong }}</td>
            <td>
                <span class="badge {{ $sp->trangThai == 'Còn hàng' ? 'bg-success' : 'bg-danger' }}">
                    {{ $sp->trangThai }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.sanpham.edit', $sp) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('admin.sanpham.destroy', $sp) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa sản phẩm này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $sanPhams->links() }}
@endsection