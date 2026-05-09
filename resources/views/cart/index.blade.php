@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">🛒 Giỏ hàng của bạn</h2>

            @if($items->isEmpty())
                <div class="alert alert-info">
                    Giỏ hàng trống. <a href="{{ route('home') }}">Mua sắm ngay</a>
                </div>
            @else

            <form action="{{ route('cart.checkout') }}" method="POST" id="checkoutForm">
                @csrf

                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="checkAll"></th>
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
                        @php
                            $sp = $item->sanPham;
                            $giaHienTai = $item->gia ?? $sp->gia;
                            $giaGoc     = $sp->gia;
                            $thanhTien  = $giaHienTai * $item->soLuong;
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" 
                                       name="selected_items[]" 
                                       value="{{ $item->idDonTrongGioHang }}" 
                                       class="item-check">
                            </td>
                            <td>
                                <img src="{{ asset('images/' . ($sp->hinh_anh ?? 'no-image.jpg')) }}" 
                                     width="60" alt="{{ $sp->tenSanPham }}">
                            </td>
                            <td>{{ $sp->tenSanPham }}</td>
                            <td>
                                @if($giaHienTai < $giaGoc)
                                    <span class="text-danger fw-bold">{{ number_format($giaHienTai) }} ₫</span><br>
                                    <small class="text-muted text-decoration-line-through">
                                        {{ number_format($giaGoc) }} ₫
                                    </small>
                                @else
                                    <span class="fw-bold">{{ number_format($giaHienTai) }} ₫</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('cart.update', ['id' => $item->idDonTrongGioHang, 'action' => 'decrease']) }}" 
                                   class="btn btn-sm btn-outline-secondary">-</a>
                                <span class="mx-3">{{ $item->soLuong }}</span>
                                <a href="{{ route('cart.update', ['id' => $item->idDonTrongGioHang, 'action' => 'increase']) }}" 
                                   class="btn btn-sm btn-outline-secondary">+</a>
                            </td>
                            <td class="fw-bold text-end">
                                {{ number_format($thanhTien) }} ₫
                            </td>
                            <td>
                                <a href="{{ route('cart.remove', $item->idDonTrongGioHang) }}" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Xóa sản phẩm này?')">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="row mt-4">
                    <div class="col-md-8">
                        <button type="button" onclick="window.history.back()" class="btn btn-secondary">
                            ← Tiếp tục mua sắm
                        </button>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Thanh toán các sản phẩm đã chọn →
                        </button>
                    </div>
                </div>
            </form>

            @endif
        </div>
    </div>
</div>

<script>
document.getElementById('checkAll').addEventListener('change', function () {
    document.querySelectorAll('.item-check').forEach(cb => cb.checked = this.checked);
});
</script>
@endsection