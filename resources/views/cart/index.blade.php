@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">

            <h2 class="mb-4">🛒 Giỏ hàng của bạn</h2>

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Giỏ hàng trống --}}
            @if($items->isEmpty())

                <div class="alert alert-info">
                    Giỏ hàng trống.
                    <a href="{{ route('home') }}">Mua sắm ngay</a>
                </div>

            @else

            {{-- FORM CHECKOUT --}}
            <form action="{{ route('cart.checkout') }}" method="POST" id="checkoutForm">
                @csrf

                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="checkAll">
                            </th>

                            <th width="90">Hình</th>

                            <th>Sản phẩm</th>

                            <th width="180">Giá</th>

                            <th width="170">Số lượng</th>

                            <th width="180">Thành tiền</th>

                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($items as $item)

                        @php
                            $sp = $item->sanPham;

                            $giaHienTai = $item->gia ?? $sp->gia;

                            $giaGoc = $sp->gia;

                            $thanhTien = $giaHienTai * $item->soLuong;
                        @endphp

                        <tr>

                            {{-- Checkbox --}}
                            <td class="text-center">
                                <input type="checkbox"
                                       name="selected_items[]"
                                       value="{{ $item->idDonTrongGioHang }}"
                                       class="item-check">
                            </td>

                            {{-- Hình --}}
                            <td class="text-center">
                                <img src="{{ asset('images/' . ($sp->hinh_anh ?? 'no-image.jpg')) }}"
                                     width="60"
                                     alt="{{ $sp->tenSanPham }}">
                            </td>

                            {{-- Tên sản phẩm --}}
                            <td>
                                {{ $sp->tenSanPham }}
                            </td>

                            {{-- Giá --}}
                            <td>

                                @if($giaHienTai < $giaGoc)

                                    <span class="text-danger fw-bold">
                                        {{ number_format($giaHienTai) }} ₫
                                    </span>

                                    <br>

                                    <small class="text-muted text-decoration-line-through">
                                        {{ number_format($giaGoc) }} ₫
                                    </small>

                                @else

                                    <span class="fw-bold">
                                        {{ number_format($giaHienTai) }} ₫
                                    </span>

                                @endif

                            </td>

                            {{-- Số lượng --}}
                            <td class="text-center">

                                <a href="{{ route('cart.update', [
                                        'id' => $item->idDonTrongGioHang,
                                        'action' => 'decrease'
                                    ]) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    -
                                </a>

                                <span class="mx-3 fw-bold">
                                    {{ $item->soLuong }}
                                </span>

                                <a href="{{ route('cart.update', [
                                        'id' => $item->idDonTrongGioHang,
                                        'action' => 'increase'
                                    ]) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    +
                                </a>

                            </td>

                            {{-- Thành tiền --}}
                            <td class="fw-bold text-end text-danger">
                                {{ number_format($thanhTien) }} ₫
                            </td>

                            {{-- Xóa --}}
                            <td class="text-center">

                                {{-- QUAN TRỌNG:
                                     form xóa phải nằm riêng,
                                     KHÔNG được lồng trong form checkout
                                --}}
                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="removeItem({{ $item->idDonTrongGioHang }})">
                                    Xóa
                                </button>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>
                </table>

                {{-- Action buttons --}}
                <div class="row mt-4">

                    <div class="col-md-8">

                        <button type="button"
                                onclick="window.history.back()"
                                class="btn btn-secondary">

                            ← Tiếp tục mua sắm

                        </button>

                    </div>

                    <div class="col-md-4 text-end">

                        <button type="submit"
                                class="btn btn-primary btn-lg">

                            Thanh toán các sản phẩm đã chọn →

                        </button>

                    </div>

                </div>

            </form>

            {{-- FORM XÓA RIÊNG --}}
            <form id="removeForm" method="POST" style="display:none;">
                @csrf
            </form>

            @endif

        </div>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const checkAll = document.getElementById('checkAll');

    if (checkAll) {

        checkAll.addEventListener('change', function () {

            document.querySelectorAll('.item-check').forEach(function (cb) {

                cb.checked = checkAll.checked;

            });

        });

    }

});

function removeItem(id) {

    if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {

        let form = document.getElementById('removeForm');

        form.action = '/cart/remove/' + id;

        form.submit();  
    }
}

</script>
@endsection