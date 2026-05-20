@extends('layouts.app')

@section('title', 'Quản lý Khuyến mãi')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🎟️ Quản lý Khuyến mãi</h2>
        <a href="{{ route('admin.khuyenmai.create') }}" class="btn btn-success">
            + Thêm khuyến mãi mới
        </a>
    </div>

    @if($khuyenMais->isEmpty())
        <div class="alert alert-info">Chưa có khuyến mãi nào.</div>
    @else
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Mã KM</th>
                    <th>Tên khuyến mãi</th>
                    <th>Mô tả</th>
                    <th>% Giảm</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($khuyenMais as $km)
                <tr>
                    <td><strong>#{{ $km->idKhuyenMai }}</strong></td>
                    <td>
                        <span class="badge bg-primary">{{ $km->maKhuyenMai ?? 'Chưa có' }}</span>
                    </td>
                    <td>{{ $km->tenKhuyenMai ?? 'N/A' }}</td>
                    <td>{{ $km->moTaKhuyenMai }}</td>
                    <td class="text-center">
                        @if($km->phanTramGiam > 0)
                            <span class="badge bg-danger fs-6">{{ $km->phanTramGiam }}%</span>
                        @else
                            <span class="badge bg-secondary">0%</span>
                        @endif
                    </td>
                    <td>{{ $km->ngayBatDau }}</td>
                    <td>{{ $km->ngayKetThuc }}</td>
                    <td class="text-center">
                        @if($km->trangThai == 1)
                            <span class="badge bg-success">✅ Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">⛔ Tắt</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.khuyenmai.edit', $km) }}" 
                           class="btn btn-sm btn-warning">
                            Sửa
                        </a>
                        
                        <form action="{{ route('admin.khuyenmai.destroy', $km) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Bạn có chắc muốn xóa khuyến mãi này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $khuyenMais->links() }}
        </div>
    @endif
</div>
@endsection