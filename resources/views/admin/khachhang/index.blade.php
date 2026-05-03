@extends('layouts.app')

@section('content')
<div class="container">

    <h3>👤 Quản lý khách hàng</h3>

    <!-- 🔍 SEARCH -->
    <form method="GET" class="mb-3 d-flex">
        <input type="text" name="q" class="form-control me-2" placeholder="Tìm tên..." value="{{ request('q') }}">
        <button class="btn btn-warning">Tìm</button>
    </form>

    <a href="{{ route('admin.khachhang.create') }}" class="btn btn-success mb-3">
        + Thêm khách hàng
    </a>

    <table class="table table-dark table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($khachHangs as $kh)
            <tr>
                <td>{{ $kh->idNguoiDung }}</td>
                <td>{{ $kh->tenNguoiDung }}</td>
                <td>{{ $kh->email }}</td>
                <td>
                    <a href="{{ route('admin.khachhang.edit', $kh->idNguoiDung) }}" 
                       class="btn btn-warning btn-sm">Sửa</a>

                    <form action="{{ route('admin.khachhang.destroy', $kh->idNguoiDung) }}" 
                          method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Xóa?')">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $khachHangs->links() }}

</div>
@endsection