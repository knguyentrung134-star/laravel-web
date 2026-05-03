@extends('layouts.app')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-store"></i> Quản lý Sản phẩm
        </h2>
        <a href="{{ route('admin.sanpham.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus"></i> Thêm sản phẩm mới
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách sản phẩm ({{ $sanPhams->total() }} sản phẩm)</h5>
                <div class="text-muted small">
                    Trang {{ $sanPhams->currentPage() }} / {{ $sanPhams->lastPage() }}
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="8%">Hình</th>
                            <th>Tên sản phẩm</th>
                            <th class="text-end">Giá</th>
                            <th class="text-center">Tồn kho</th>
                            <th class="text-center">Trạng thái</th>
                            <th width="15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sanPhams as $sp)
                        <tr>
                            <td class="fw-bold text-center">{{ $sp->idSanPham }}</td>
                            <td>
                                @if($sp->hinh_anh)
                                    <img src="{{ asset('images/' . $sp->hinh_anh) }}" 
                                         class="rounded" width="55" height="55" style="object-fit: cover;" alt="">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 55px; height: 55px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $sp->tenSanPham }}</strong>
                                @if($sp->theLoai)
                                    <small class="text-muted d-block">{{ $sp->theLoai }}</small>
                                @endif
                            </td>
                            <td class="text-end fw-bold">{{ number_format($sp->gia) }} ₫</td>
                            <td class="text-center">
                                <span class="badge fs-6 {{ $sp->soLuong > 0 ? 'bg-success' : 'bg-warning' }}">
                                    {{ number_format($sp->soLuong) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $sp->trangThai == 'Còn hàng' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $sp->trangThai }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.sanpham.edit', $sp) }}" 
                                   class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.sanpham.destroy', $sp) }}" 
                                      method="POST" class="d-inline" 
                                      onsubmit="return confirm('Xóa sản phẩm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-muted small">
                    Hiển thị <strong>{{ $sanPhams->firstItem() ?? 0 }}</strong> - 
                    <strong>{{ $sanPhams->lastItem() ?? 0 }}</strong> 
                    trong tổng <strong>{{ $sanPhams->total() }}</strong> sản phẩm
                </div>
                
                <div>
                    {{ $sanPhams->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection