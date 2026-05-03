@extends('layouts.app')

@section('content')
<div class="container">
    <h3>➕ Thêm khách hàng</h3>

    <form method="POST" action="{{ route('admin.khachhang.store') }}">
        @csrf

        <input name="tenNguoiDung" class="form-control mb-2" placeholder="Tên">
        <input name="email" class="form-control mb-2" placeholder="Email">
        <input type="password" name="matKhau" class="form-control mb-2" placeholder="Mật khẩu">

        <button class="btn btn-success">Lưu</button>
    </form>
</div>
@endsection