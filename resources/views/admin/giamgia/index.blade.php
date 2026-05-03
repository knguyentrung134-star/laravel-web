@extends('layouts.app')

@section('title', 'Quản lý giảm giá')

@section('content')
<div class="container">

    <h3 class="mb-3">🔥 Chương trình giảm giá</h3>

    <a href="{{ route('admin.giamgia.create') }}" class="btn btn-primary mb-3">
        ➕ Thêm mới
    </a>

    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Thể loại</th>
                <th>%</th>
                <th>Thời gian</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
            @foreach($ds as $item)
            <tr>
                <td>{{ $item->tenChuongTrinh }}</td>
                <td>{{ $item->theLoai }}</td>
                <td>{{ $item->phanTramGiam }}%</td>
                <td>
                    {{ $item->ngayBatDau }} → {{ $item->ngayKetThuc }}
                </td>
                <td>
                    <!-- ✅ FIX -->
                    <a href="{{ route('admin.giamgia.edit', $item) }}" class="btn btn-warning btn-sm">Sửa</a>

                    <form action="{{ route('admin.giamgia.destroy', $item) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection