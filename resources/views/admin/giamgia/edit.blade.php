@extends('layouts.app')

@section('content')
<div class="container">
    <h3>✏️ Sửa chương trình giảm giá</h3>

    <form action="{{ route('admin.giamgia.update', $item) }}" method="POST">
        @csrf
        @method('PUT')

        <input name="tenChuongTrinh" value="{{ $item->tenChuongTrinh }}" class="form-control mb-2">

        <input name="theLoai" value="{{ $item->theLoai }}" class="form-control mb-2">

        <input name="phanTramGiam" value="{{ $item->phanTramGiam }}" class="form-control mb-2">

        <input name="ngayBatDau" type="date" value="{{ $item->ngayBatDau }}" class="form-control mb-2">

        <input name="ngayKetThuc" type="date" value="{{ $item->ngayKetThuc }}" class="form-control mb-2">

        <button class="btn btn-warning">Cập nhật</button>
        <a href="{{ route('admin.giamgia.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection