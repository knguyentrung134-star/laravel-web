<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use Illuminate\Http\Request;
use App\Models\ChuongTrinhGiamGia;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = SanPham::where('trangThai', 'Còn hàng');

        // lọc thể loại
        if ($request->has('theLoai') && $request->theLoai != '') {
            $query->where('theLoai', $request->theLoai);
        }

        $sanPhams = $query->paginate(16);
        $sanPhams->appends($request->all());    

        // 🔥 giảm giá
        $giamGias = ChuongTrinhGiamGia::whereDate('ngayBatDau', '<=', Carbon::now())
            ->whereDate('ngayKetThuc', '>=', Carbon::now())
            ->get()
            ->keyBy('theLoai');

        foreach ($sanPhams as $sp) {
            if (isset($giamGias[$sp->theLoai])) {
                $gg = $giamGias[$sp->theLoai];

                $sp->gia_goc = $sp->gia;
                $sp->gia = $sp->gia - ($sp->gia * $gg->phanTramGiam / 100);
                $sp->phanTramGiam = $gg->phanTramGiam;
            }
        }

        // 🔥 popup khuyến mãi
        $khuyenMai = ChuongTrinhGiamGia::whereDate('ngayBatDau', '<=', Carbon::now())
            ->whereDate('ngayKetThuc', '>=', Carbon::now())
            ->get();

        $theLoais = SanPham::whereNotNull('theLoai')
            ->distinct()
            ->pluck('theLoai');

        return view('home', compact('sanPhams', 'theLoais', 'khuyenMai'));
    }
    public function search(Request $request)
{
    $keyword = $request->q;

    $sanPhams = SanPham::query()                    // ← Nên dùng query() cho rõ
        ->where('trangThai', 'Còn hàng')
        ->when($keyword, function ($query) use ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('tenSanPham', 'LIKE', "%{$keyword}%")
                  ->orWhere('moTa', 'LIKE', "%{$keyword}%")
                  ->orWhere('theLoai', 'LIKE', "%{$keyword}%");
            });
        })
        ->paginate(16)
        ->appends(['q' => $keyword]);

    $today = Carbon::today();

    $khuyenMai = ChuongTrinhGiamGia::where('ngayBatDau', '<=', $today)
        ->where('ngayKetThuc', '>=', $today)
        ->get();

    $theLoais = SanPham::whereNotNull('theLoai')
        ->distinct()
        ->pluck('theLoai');

    $showPopup = true;

    return view('home', compact('sanPhams', 'theLoais', 'khuyenMai', 'showPopup'));
}

}