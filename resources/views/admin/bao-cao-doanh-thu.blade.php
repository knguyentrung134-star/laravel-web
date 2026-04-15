@extends('layouts.app')  {{-- Đổi thành layout admin của bạn nếu khác (ví dụ: admin.master, layouts.admin) --}}

@section('title', 'Báo Cáo Doanh Thu')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">📊 Báo Cáo Doanh Thu</h1>

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

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng doanh thu</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRevenue, 0) }} ₫</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng đơn hàng</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Giá trị trung bình / đơn</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($avgOrderValue, 0) }} ₫</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">📈 Biểu đồ doanh thu theo ngày</div>
        <div class="card-body">
            <canvas id="revenueChart" height="120"></canvas>
        </div>
    </div>

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
                    @foreach($revenueByDate as $date => $revenue)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                        <td class="text-end fw-bold">{{ number_format($revenue, 0) }} ₫</td>
                        <td class="text-end">{{ $donHangs->where('created_at', 'LIKE', $date . '%')->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($revenueByDate->keys()),
            datasets: [{
                label: 'Doanh thu (₫)',
                data: @json($revenueByDate->values()),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
@endsection