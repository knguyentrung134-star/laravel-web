@extends('layouts.app')

@section('title', $sanpham->tenSanPham)

@section('content')
<div class="row">
    <!-- Hình ảnh sản phẩm -->
    <div class="col-md-5">
        @if($sanpham->hinh_anh)
            <img src="{{ asset('images/' . $sanpham->hinh_anh) }}" 
                 class="img-fluid rounded shadow-sm" style="width:100%; height:auto;" 
                 alt="{{ $sanpham->tenSanPham }}">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px; border-radius: 10px;">
                <h1 class="text-secondary">🎵</h1>
            </div>
        @endif
    </div>

    <!-- Thông tin sản phẩm -->
    <div class="col-md-7">
        <h2>{{ $sanpham->tenSanPham }}</h2>
        <h3 class="text-danger mb-3">{{ number_format($sanpham->gia) }} ₫</h3>
        
        <p>{{ $sanpham->moTa ?? 'Không có mô tả cho sản phẩm này.' }}</p>

        <div class="mb-4">
            <strong>Trạng thái: </strong>
            <span class="badge bg-{{ $sanpham->trangThai === 'Còn hàng' ? 'success' : 'danger' }}">
                {{ $sanpham->trangThai }}
            </span>
        </div>

        <!-- Form Đánh giá -->
        <div class="card mt-5">
            <div class="card-header bg-primary text-white">
                <h5>Đánh giá sản phẩm này</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('danhgia.store', $sanpham) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Bạn đánh giá bao nhiêu sao?</label>
                        <select name="soSao" class="form-select" required>
                            <option value="">-- Chọn số sao --</option>
                            <option value="5">5 ⭐ - Tuyệt vời</option>
                            <option value="4">4 ⭐ - Tốt</option>
                            <option value="3">3 ⭐ - Bình thường</option>
                            <option value="2">2 ⭐ - Không hài lòng</option>
                            <option value="1">1 ⭐ - Rất kém</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nội dung đánh giá</label>
                        <textarea name="noiDung" class="form-control" rows="4" 
                                  placeholder="Viết nhận xét của bạn..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                </form>
            </div>
        </div>

        <!-- Hiển thị các đánh giá đã có -->
        <div class="mt-5">
            <h5>Các đánh giá từ khách hàng ({{ $sanpham->danhGias->count() }})</h5>
            
            @if($sanpham->danhGias->isEmpty())
                <div class="alert alert-info">Chưa có đánh giá nào cho sản phẩm này.</div>
            @else
                @foreach($sanpham->danhGias as $danhgia)
                <div class="border p-3 mb-3 rounded">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $danhgia->nguoiDung->tenNguoiDung ?? 'Khách hàng ẩn danh' }}</strong>
                        <span class="text-warning">{{ str_repeat('★', $danhgia->soSao) }}</span>
                    </div>
                    <p class="mt-2 mb-1">{{ $danhgia->noiDung }}</p>
                    <small class="text-muted">Đánh giá lúc: {{ $danhgia->created_at ? $danhgia->created_at->format('d/m/Y H:i') : '' }}</small>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection