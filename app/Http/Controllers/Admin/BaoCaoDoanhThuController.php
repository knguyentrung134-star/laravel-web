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
        // ===============================
        // PARSE DATE
        // ===============================
        $parseDate = function ($date) {
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
        };

        // ===============================
        // DATE RANGE
        // ===============================
        $startDate = $parseDate($request->start_date)
            ?? Carbon::now()->subDays(30)->format('Y-m-d');

        $endDate = $parseDate($request->end_date)
            ?? Carbon::now()->format('Y-m-d');

        // ===============================
        // TRẠNG THÁI TÍNH DOANH THU
        // ===============================
        $trangThaiDoanhThu = ['Hoàn thành'];

        // ===============================
        // QUERY
        // ===============================
        $donHangs = DonHang::whereBetween('ngayLap', [$startDate, $endDate])
            ->whereIn('trangThai', $trangThaiDoanhThu)
            ->get();

        // ===============================
        // TỔNG QUAN
        // ===============================
        $totalRevenue = $donHangs->sum('tongThanhTien');
        $totalOrders  = $donHangs->count();
        $avgOrderValue = $totalOrders > 0
            ? round($totalRevenue / $totalOrders)
            : 0;

        // ===============================
        // GROUP THEO NGÀY (QUAN TRỌNG)
        // ===============================
        $revenueByDate = $donHangs
            ->groupBy(function ($order) {
                return Carbon::parse($order->ngayLap)->format('Y-m-d');
            })
            ->map(function ($group) {
                return [
                    'doanh_thu' => $group->sum('tongThanhTien'),
                    'so_don'    => $group->count(),
                ];
            })
            ->sortKeys();

        // ===============================
        // RETURN VIEW
        // ===============================
        return view('admin.bao-cao-doanh-thu', compact(
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'revenueByDate',
            'startDate',
            'endDate'
        ));
    }
}