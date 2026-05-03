@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>🛒 Giỏ hàng của bạn</h2>

        @if($items->isEmpty())
            <div class="alert alert-info">Giỏ hàng trống. <a href="/">Mua sắm ngay</a></div>
        @else

        <form action="{{ route('checkout.index') }}" method="GET">

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

                        $giam = \App\Models\ChuongTrinhGiamGia::where('theLoai', $sp->theLoai)
                            ->where('ngayBatDau', '<=', now())
                            ->where('ngayKetThuc', '>=', now())
                            ->first();

                        $giaGoc = $sp->gia;
                        $giaMoi = $giam ? $giaGoc * (1 - $giam->phanTramGiam / 100) : $giaGoc;
                        $thanhTien = $giaMoi * $item->soLuong;
                    @endphp

                    <tr>
                        <td>
                            <input type="checkbox" name="selected[]" value="{{ $item->idDonTrongGioHang }}" class="item-check">
                        </td>

                        <td>
                            <img src="{{ asset('images/' . $sp->hinh_anh) }}" width="60">
                        </td>

                        <td>{{ $sp->tenSanPham }}</td>

                        <td>
                            @if($giam)
                                <span class="text-danger fw-bold">
                                    {{ number_format($giaMoi) }} ₫
                                </span><br>
                                <small class="text-muted text-decoration-line-through">
                                    {{ number_format($giaGoc) }} ₫
                                </small>
                            @else
                                {{ number_format($giaMoi) }} ₫
                            @endif
                        </td>

                        <!-- 🔥 FIX QUAN TRỌNG -->
                        <td class="text-center">
                            <a href="{{ route('cart.update', ['id' => $item->idDonTrongGioHang, 'action' => 'decrease']) }}"
                               class="btn btn-sm btn-outline-secondary">-</a>

                            <span class="mx-2">{{ $item->soLuong }}</span>

                            <a href="{{ route('cart.update', ['id' => $item->idDonTrongGioHang, 'action' => 'increase']) }}"
                               class="btn btn-sm btn-outline-secondary">+</a>
                        </td>

                        <td>{{ number_format($thanhTien) }} ₫</td>

                        <td>
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->idDonTrongGioHang }}">
                                <button class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between">
                <a href="/" class="btn btn-secondary">Tiếp tục mua</a>

                <button type="submit" class="btn btn-success">
                    Thanh toán sản phẩm đã chọn
                </button>
            </div>

        </form>

        @endif
    </div>
</div>

<script>
document.getElementById('checkAll').addEventListener('change', function () {
    document.querySelectorAll('.item-check').forEach(cb => cb.checked = this.checked);
});
</script>

@endsection