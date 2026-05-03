@extends('layouts.app')

@section('title', 'Đổi / Trả hàng')

@section('content')
<div class="container">
    <h2 class="mb-4">🔄 Đổi / Trả hàng</h2>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="gui-yeu-cau-tab" data-bs-toggle="tab" data-bs-target="#gui-yeu-cau" type="button">
                Gửi yêu cầu đổi/trả mới
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="lich-su-tab" data-bs-toggle="tab" data-bs-target="#lich-su" type="button">
                Lịch sử đổi/trả hàng
            </button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <!-- Tab 1: Gửi yêu cầu mới -->
        <div class="tab-pane fade show active" id="gui-yeu-cau">
            @if($donHangs->isEmpty())
                <div class="alert alert-info">Hiện tại bạn không có đơn hàng nào đủ điều kiện đổi/trả.</div>
            @else
                @foreach($donHangs as $don)
                <div class="card mb-4">
                    <div class="card-header">
                        Đơn hàng #{{ $don->idDonHang }} - {{ $don->ngayLap }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('trahang.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="idDonHang" value="{{ $don->idDonHang }}">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Lý do đổi/trả</label>
                                <textarea name="lyDo" class="form-control" rows="4" required placeholder="Nhập lý do..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-warning">Gửi yêu cầu đổi/trả</button>
                        </form>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- Tab 2: Lịch sử đổi/trả -->
        <div class="tab-pane fade" id="lich-su">
            @if($traHangs->isEmpty())
                <div class="alert alert-info">Bạn chưa có yêu cầu đổi/trả nào.</div>
            @else
                @foreach($traHangs as $tra)
                <div class="card mb-4">
                    <div class="card-header">
                        Yêu cầu #{{ $tra->idTraHang }} - Đơn hàng #{{ $tra->idDonHang }}
                    </div>
                    <div class="card-body">
                        <p><strong>Lý do:</strong> {{ $tra->lyDo }}</p>
                        <p><strong>Ngày yêu cầu:</strong> {{ $tra->ngayTra }}</p>
                        <small class="text-muted">Trạng thái: Đang xử lý</small>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection