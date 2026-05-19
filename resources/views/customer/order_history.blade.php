@extends('layouts.app')

@section('title', 'Lịch sử mua hàng')

@section('content')
<div class="container">
    <h2 class="mb-4">📦 Lịch sử mua hàng của bạn</h2>

    @if($donHangs->isEmpty())
        <div class="alert alert-info">
            Bạn chưa có đơn hàng nào.
        </div>
    @else

        @foreach($donHangs as $don)

        @php
            $badgeClass = match($don->trangThai) {
                'Hoàn thành' => 'success',
                'da_huy', 'Đã hủy' => 'danger',
                'Đang xử lý' => 'primary',
                'Chờ xác nhận' => 'warning',
                default => 'secondary'
            };

            $trangThaiText = match($don->trangThai) {
                'da_huy' => 'Đã hủy',
                default => $don->trangThai
            };
        @endphp

        <div class="card mb-4 shadow-sm border-0">

            {{-- Header --}}
            <div class="card-header d-flex justify-content-between align-items-center bg-white">

                <strong class="fs-5">
                    📄 Mã đơn hàng: #{{ $don->idDonHang }}
                </strong>

                <div>
                    <small class="text-muted">
                        Ngày: {{ $don->ngayLap }}
                    </small>

                    <span class="badge bg-{{ $badgeClass }} ms-2 px-3 py-2 rounded-pill">
                        {{ $trangThaiText }}
                    </span>
                </div>

            </div>

            {{-- Body --}}
            <div class="card-body">

                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th width="120">Số lượng</th>
                            <th width="180">Đơn giá</th>
                            <th width="180">Thành tiền</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($don->chiTietDonHangs as $ct)

                        <tr>

                            <td>
                                {{ $ct->sanPham->tenSanPham ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $ct->soLuong }}
                            </td>

                            <td class="text-end">

                                @if($ct->sanPham && $ct->donGia < $ct->sanPham->gia)

                                    <span class="text-danger fw-bold">
                                        {{ number_format($ct->donGia) }} ₫
                                    </span>

                                    <br>

                                    <small class="text-muted text-decoration-line-through">
                                        {{ number_format($ct->sanPham->gia) }} ₫
                                    </small>

                                @else

                                    {{ number_format($ct->donGia) }} ₫

                                @endif

                            </td>

                            <td class="fw-bold text-danger text-end">
                                {{ number_format($ct->soLuong * $ct->donGia) }} ₫
                            </td>

                        </tr>

                        @endforeach

                    </tbody>
                </table>

            </div>

            {{-- Footer --}}
            <div class="card-footer bg-light">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <strong>
                            Tổng thanh toán (sau khuyến mãi):
                        </strong>

                        <span class="fs-5 text-danger fw-bold">
                            {{ number_format($don->tongThanhTien) }} ₫
                        </span>
                    </div>

                    <div>
                        <span class="badge bg-{{ $badgeClass }} px-3 py-2 rounded-pill">
                            {{ $trangThaiText }}
                        </span>
                    </div>

                </div>

                {{-- Hủy đơn --}}
                @if($don->canCancel())

                <div class="mt-3">

                    <form action="{{ route('donhang.huy', $don->idDonHang) }}"
                          method="POST"
                          onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')">

                        @csrf

                        <div class="row g-2">

                            <div class="col-md-8">

                                <select name="ly_do_huy"
                                        class="form-select"
                                        required>

                                    <option value="">
                                        Chọn lý do hủy...
                                    </option>

                                    <option value="Thay đổi ý định">
                                        Thay đổi ý định
                                    </option>

                                    <option value="Đặt nhầm sản phẩm">
                                        Đặt nhầm sản phẩm
                                    </option>

                                    <option value="Tìm thấy giá rẻ hơn">
                                        Tìm thấy giá rẻ hơn
                                    </option>

                                    <option value="Khác">
                                        Lý do khác
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-4">

                                <button type="submit"
                                        class="btn btn-danger w-100">

                                    <i class="fas fa-times me-1"></i>
                                    Hủy đơn hàng

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

                @endif

            </div>

        </div>

        @endforeach

        <div class="mt-4">
            {{ $donHangs->links() }}
        </div>

    @endif
</div>
@endsection