@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Bên trái: Thông tin đơn hàng -->
        <div class="col-lg-8">
            <h3 class="mb-4">Thông tin đơn hàng</h3>

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Giá</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    @php
                        $sp = $item->sanPham;
                        $giaGoc = $sp->gia ?? 0;
                        $giaHienTai = $item->giaSauGiam ?? $item->gia ?? $giaGoc;
                        $thanhTien = $giaHienTai * $item->soLuong;
                    @endphp

                    <tr>
                        <td>{{ $sp->tenSanPham }}</td>
                        <td class="text-center">{{ $item->soLuong }}</td>
                        <td class="text-end">
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
                        <td class="text-end fw-bold">
                            {{ number_format($thanhTien) }} ₫
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">Tổng tiền gốc:</td>
                        <td class="text-end">{{ number_format($total + $discount) }} ₫</td>
                    </tr>

                    @if(isset($discount) && $discount > 0)
                    <tr class="fw-bold text-success">
                        <td colspan="3" class="text-end">Tiết kiệm (khuyến mãi):</td>
                        <td class="text-end">- {{ number_format($discount) }} ₫</td>
                    </tr>
                    @endif

                    <tr class="table-active fw-bold fs-5">
                        <td colspan="3" class="text-end">Thanh toán:</td>
                        <td class="text-end text-danger">{{ number_format($total) }} ₫</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Bên phải: Form thanh toán -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin giao hàng & Thanh toán</h5>
                </div>
                <div class="card-body">

                    <!-- Form áp dụng mã giảm giá (GET) -->
                    <form method="GET" action="{{ route('checkout.index') }}" class="mb-4">
                        @csrf
                        <label class="form-label fw-bold">Mã giảm giá</label>
                        <div class="input-group">
                            <input type="text" 
                                   name="ma_giam_gia" 
                                   class="form-control" 
                                   placeholder="Nhập mã giảm giá (ví dụ: 80%)" 
                                   value="{{ $maGiamGia ?? '' }}">
                            <button type="submit" class="btn btn-outline-primary">Áp dụng</button>
                        </div>
                    </form>

                    <!-- Thông báo -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Form đặt hàng chính (POST) -->
                    <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <!-- Họ và tên -->
                        <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="tenKhachHang" class="form-control" 
                                   value="{{ old('tenKhachHang', $khachHang->tenKhachHang ?? '') }}" required>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea name="diaChi" class="form-control" rows="2" required>{{ old('diaChi', $khachHang->diaChi ?? '') }}</textarea>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="soDienThoai" class="form-control" 
                                   value="{{ old('soDienThoai', $khachHang->soDienThoai ?? '') }}" required>
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Phương thức thanh toán</label>
                            <select name="phuongThuc" id="phuongThuc" class="form-select" required>
                                <option value="TienMat">Tiền mặt khi nhận hàng (COD)</option>
                                <option value="ChuyenKhoan">Chuyển khoản ngân hàng</option>
                                <option value="TheTinDung">Thẻ tín dụng / Thẻ ghi nợ</option>
                                <option value="ViDienTu">Ví điện tử (Momo, ZaloPay...)</option>
                            </select>
                        </div>

                        <!-- QR Code -->
                        <div id="qrCodeSection" class="mb-4" style="display: none;">
                            <!-- ... giữ nguyên phần QR Code của bạn ... -->
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white text-center py-2">
                                    <h6 class="mb-0">Quét mã QR để thanh toán</h6>
                                </div>
                                <div class="card-body text-center p-3">
                                    <img id="qrImage" src="" class="img-fluid rounded shadow" style="max-width: 280px;">
                                    <div class="mt-3 small text-muted">
                                        <p class="mb-1 fw-bold">Ngân hàng BIDV</p>
                                        <p class="mb-1">Số tài khoản: <strong>2601663003</strong></p>
                                        <p>Chủ tài khoản: NGUYEN TRUNG KIEN</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-check-circle"></i> Xác nhận đặt hàng
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('phuongThuc');
    const qrSection = document.getElementById('qrCodeSection');
    const qrImage = document.getElementById('qrImage');
    const baseUrl = "https://img.vietqr.io/image/BIDV-2601663003-compact2.png";

    function updateQR() {
        if (select.value === 'ChuyenKhoan') {
            const amount = {{ (int) $total }};
            const newSrc = `${baseUrl}?amount=${amount}&addInfo=DH{{ auth()->id() ?? 'KH' }}&accountName=NGUYEN%20TRUNG%20KIEN`;
            qrImage.src = newSrc;
            qrSection.style.display = 'block';
        } else {
            qrSection.style.display = 'none';
        }
    }

    select.addEventListener('change', updateQR);
    updateQR();
});
</script>
@endsection