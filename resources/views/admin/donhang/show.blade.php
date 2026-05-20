@extends('layouts.app')

@section('title', 'Chi tiết Đơn hàng #{{ $donhang->idDonHang }}')

@section('content')
<div class="row">
    <!-- Bên trái: Chi tiết đơn hàng -->
    <div class="col-md-8">
        <h3 class="mb-4">
            Chi tiết Đơn hàng #{{ $donhang->idDonHang }}
        </h3>
        
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Sản phẩm</th>
                    <th width="120">Số lượng</th>
                    <th width="200">Đơn giá</th>
                    <th width="180">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donhang->chiTietDonHangs as $ct)
                    @php
                        $giaGoc = $ct->sanPham->gia ?? 0;
                        $donGia = $ct->donGia ?? $giaGoc;   // Quan trọng: dùng giá đã lưu khi đặt hàng
                    @endphp

                    <tr>
                        <td>
                            {{ $ct->sanPham->tenSanPham ?? 'Sản phẩm không tồn tại' }}
                        </td>
                        <td class="text-center">
                            {{ $ct->soLuong }}
                        </td>
                        <td class="text-end">
                            @if($donGia < $giaGoc)
                                <!-- Có giảm giá -->
                                <span class="text-danger fw-bold">
                                    {{ number_format($donGia) }} ₫
                                </span>
                                <br>
                                <small class="text-muted text-decoration-line-through">
                                    {{ number_format($giaGoc) }} ₫
                                </small>
                                <small class="text-success ms-1">
                                    (-{{ round((($giaGoc - $donGia) / $giaGoc * 100)) }}%)
                                </small>
                            @else
                                <!-- Không giảm -->
                                <span class="fw-bold">
                                    {{ number_format($donGia) }} ₫
                                </span>
                            @endif
                        </td>
                        <td class="fw-bold text-danger text-end">
                            {{ number_format($ct->soLuong * $donGia) }} ₫
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="3" class="text-end">Tổng thanh toán sau giảm giá:</td>
                    <td class="text-danger">{{ number_format($donhang->tongThanhTien) }} ₫</td>
                </tr>
                
                @if($donhang->giamGia > 0)
                <tr class="text-success fw-bold">
                    <td colspan="3" class="text-end">Tổng giá trị giảm:</td>
                    <td>- {{ number_format($donhang->giamGia) }} ₫</td>
                </tr>
                @endif
            </tfoot>
        </table>
    </div>

    <!-- Bên phải: Thông tin khách hàng + Cập nhật trạng thái -->
    <div class="col-md-4">
        <!-- Thông tin khách hàng -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Thông tin khách hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Tên:</strong> {{ $donhang->khachHang->tenKhachHang ?? 'N/A' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $donhang->khachHang->diaChi ?? 'N/A' }}</p>
                <p><strong>SĐT:</strong> {{ $donhang->khachHang->soDienThoai ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Cập nhật trạng thái -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5>Cập nhật trạng thái đơn hàng</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.donhang.update', $donhang) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <select name="trangThai" class="form-select">
                            <option value="Đang xử lý" {{ $donhang->trangThai == 'Đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="Đã xác nhận" {{ $donhang->trangThai == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="Đang giao" {{ $donhang->trangThai == 'Đang giao' ? 'selected' : '' }}>Đang giao</option>
                            <option value="Hoàn thành" {{ $donhang->trangThai == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="Đã hủy" {{ $donhang->trangThai == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Cập nhật trạng thái
                    </button>
                </form>
            </div>
        </div>

        <a href="{{ route('admin.donhang.index') }}" class="btn btn-secondary w-100">
            ← Quay lại danh sách đơn hàng
        </a>
    </div>
</div>
@endsection