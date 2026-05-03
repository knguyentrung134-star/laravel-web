@extends('layouts.app')

@section('title', 'Quản lý Kho hàng')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-box"></i> Quản lý Kho hàng
        </h2>
        <div>
            <a href="{{ route('admin.kho.create') }}" class="btn btn-success me-2">
                <i class="fas fa-plus"></i> Nhập hàng mới
            </a>
            <a href="{{ route('admin.kho.lichsu') }}" class="btn btn-primary">
                <i class="fas fa-history"></i> Xem lịch sử nhập hàng
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">
                Danh sách tồn kho ({{ $hangTonKhos->total() }} sản phẩm)
            </h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">STT</th>
                        <th>Sản phẩm</th>
                        <th class="text-center">Tồn kho hiện tại</th>
                        <th class="text-end">Giá bán</th>
                        <th class="text-center">Trạng thái</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($hangTonKhos as $index => $item)
                    <tr>
                        <td class="text-center">
                            {{ $hangTonKhos->firstItem() + $index }}
                        </td>

                        <td>
                            <strong>{{ $item->tenSanPham }}</strong>
                            @if($item->theLoai)
                                <small class="text-muted d-block">
                                    {{ $item->theLoai }}
                                </small>
                            @endif
                        </td>

                        <td class="text-center">
                            <span class="badge fs-6 {{ ($item->soLuong ?? 0) > 0 ? 'bg-success' : 'bg-warning' }}">
                                {{ number_format($item->soLuong ?? 0) }}
                            </span>
                        </td>

                        <td class="text-end fw-bold">
                            {{ number_format($item->gia ?? 0) }} ₫
                        </td>

                        <td class="text-center">
                            @if(($item->soLuong ?? 0) > 0)
                                <span class="badge bg-success">Còn hàng</span>
                            @else
                                <span class="badge bg-warning">Chưa có tồn kho</span>
                            @endif
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Chưa có dữ liệu tồn kho.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-muted small">
                    Hiển thị {{ $hangTonKhos->firstItem() }} - {{ $hangTonKhos->lastItem() }} 
                    trong tổng <strong>{{ $hangTonKhos->total() }}</strong> sản phẩm
                </div>
                <div>
                    {{ $hangTonKhos->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection