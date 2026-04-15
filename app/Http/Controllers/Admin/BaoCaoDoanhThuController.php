<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonHang;
use Carbon\Carbon;

class BaoCaoDoanhThuController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Hàm parse ngày
        function parseDate($date)
        {
            if (!$date) return null;

            try {
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                    return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
                }

                if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                    return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
                }

            } catch (\Exception $e) {
                return null;
            }

            return null;
        }

        // ✅ xử lý ngày
        $startDate = parseDate($request->start_date) 
            ?? Carbon::now()->subDays(30)->format('Y-m-d');

        $endDate = parseDate($request->end_date) 
            ?? Carbon::now()->format('Y-m-d');

        // ✅ trạng thái đúng DB
        $trangThaiDoanhThu = [
            'Hoàn thành',
            'Đã thanh toán'
        ];

        // ✅ query
        $donHangs = DonHang::whereIn('trangThai', $trangThaiDoanhThu)
            ->whereBetween('ngayLap', [$startDate, $endDate])
            ->orderBy('ngayLap', 'desc')
            ->get();

        // ✅ tính toán
        $totalRevenue  = $donHangs->sum('tongThanhTien');
        $totalOrders   = $donHangs->count();
        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 0) : 0;

        // ✅ group theo ngày
        $revenueByDate = $donHangs
            ->groupBy(fn($order) => Carbon::parse($order->ngayLap)->format('Y-m-d'))
            ->map(fn($group) => $group->sum('tongThanhTien'))
            ->sortKeys();

        return view('admin.bao-cao-doanh-thu', compact(
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'revenueByDate',
            'donHangs',
            'startDate',
            'endDate'
        ));
    }
}