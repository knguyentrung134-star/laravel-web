@extends('layouts.app')

@section('title', 'Quản lý Khuyến mãi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>🎟️ Quản lý Khuyến mãi</h2>
    <a href="{{ route('admin.khuyenmai.create') }}" class="btn btn-success">+ Thêm khuyến mãi mới</a>
</div>

@if($khuyenMais->isEmpty())
    <div class="alert alert-info">Chưa có khuyến mãi nào.</div>
@else
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Mô tả</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($khuyenMais as $km)
            <tr>
                <td>{{ $km->idKhuyenMai }}</td>
                <td>{{ $km->moTaKhuyenMai }}</td>
                <td>{{ $km->ngayBatDau }}</td>
                <td>{{ $km->ngayKetThuc }}</td>
                <td>
                    <a href="{{ route('admin.khuyenmai.edit', $km) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.khuyenmai.destroy', $km) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $khuyenMais->links() }}
@endif
@endsection