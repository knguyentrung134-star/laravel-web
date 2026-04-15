@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>🛒 Giỏ hàng của bạn</h2>
        @if($items->isEmpty())
            <div class="alert alert-info">Giỏ hàng trống. <a href="/">Mua sắm ngay</a></div>
        @else
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Hình</th>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            @if($item->sanPham->hinh_anh)
                                <img src="{{ asset('images/' . $item->sanPham->hinh_anh) }}" width="60" alt="">
                            @endif
                        </td>
                        <td>{{ $item->sanPham->tenSanPham }}</td>
                        <td>{{ number_format($item->sanPham->gia) }} ₫</td>
                        <td>
                            <form action="{{ route('cart.update') }}" method="POST" class="d-flex">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->idDonTrongGioHang }}">
                                <input type="number" name="soLuong" value="{{ $item->soLuong }}" min="1" class="form-control" style="width:80px">
                                <button type="submit" class="btn btn-sm btn-outline-primary ms-2">Cập nhật</button>
                            </form>
                        </td>
                        <td>{{ number_format($item->soLuong * $item->sanPham->gia) }} ₫</td>
                        <td>
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->idDonTrongGioHang }}">
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Tổng tiền:</th>
                        <th colspan="2" class="text-danger">{{ number_format($total) }} ₫</th>
                    </tr>
                </tfoot>
            </table>

            <div class="d-flex justify-content-between">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Xóa toàn bộ giỏ</button>
                </form>
                <a href="/" class="btn btn-secondary">Tiếp tục mua sắm</a>
                <a href="{{ route('checkout.index') }}" class="btn btn-success">Thanh toán</a>
            </div>
        @endif
    </div>
</div>
@endsection