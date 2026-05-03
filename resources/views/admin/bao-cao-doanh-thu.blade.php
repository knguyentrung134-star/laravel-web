@extends('layouts.app')

@section('title', 'Báo Cáo Doanh Thu')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">📊 Báo Cáo Doanh Thu</h1>

    {{-- FILTER --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">🔍 Xem báo cáo</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Tổng doanh thu
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($totalRevenue, 0) }} ₫
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Tổng đơn hàng
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $totalOrders }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Giá trị trung bình / đơn
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($avgOrderValue, 0) }} ₫
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- TABLE --}}
    <div class="card shadow">
        <div class="card-header py-3">Chi tiết doanh thu</div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Ngày</th>
                        <th class="text-end">Doanh thu</th>
                        <th class="text-end">Số đơn</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenueByDate as $date => $data)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>

                        <td class="text-end fw-bold">
                            {{ number_format($data['doanh_thu'], 0) }} ₫
                        </td>

                        <td class="text-end">
                            {{ $data['so_don'] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            Không có dữ liệu
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection