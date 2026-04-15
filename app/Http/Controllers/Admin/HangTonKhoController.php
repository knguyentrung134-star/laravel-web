<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HangTonKho;
use App\Models\SanPham;
use App\Models\NhapHang;
use App\Models\ChiTietNhapHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HangTonKhoController extends Controller
{
    public function index()
    {
        $hangTonKhos = HangTonKho::with('sanPham')->paginate(10);
        return view('admin.kho.index', compact('hangTonKhos'));
    }

    public function create()
    {
        $sanPhams = SanPham::all();
        return view('admin.kho.create', compact('sanPhams'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'idSanPham'    => 'required|exists:sanpham,idSanPham',
            'soLuongNhap'  => 'required|integer|min:1',
            'donGiaNhap'   => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $nhapHang = NhapHang::create([
                'ngayNhap'  => now()->toDateString(),
                'trangThai' => 'Đã nhập',
                'idNhaKho'  => 1,
            ]);

            ChiTietNhapHang::create([
                'soLuong'     => $request->soLuongNhap,
                'donGiaNhap'  => $request->donGiaNhap,
                'idNhapHang'  => $nhapHang->idNhapHang,
                'idSanPham'   => $request->idSanPham,
            ]);

            // Cập nhật tồn kho
            $tonKho = HangTonKho::firstOrCreate(
                ['idSanPham' => $request->idSanPham, 'idNhaKho' => 1],
                ['soLuong' => 0]
            );
            $tonKho->increment('soLuong', $request->soLuongNhap);

            SanPham::where('idSanPham', $request->idSanPham)
                   ->increment('soLuong', $request->soLuongNhap);

            DB::commit();

            // === REDIRECT TỰ ĐỘNG SANG LỊCH SỬ ===
            return redirect()->route('admin.kho.lichsu')
                ->with('success', '✅ Nhập hàng thành công! Phiếu #' . $nhapHang->idNhapHang . ' đã được lưu vào lịch sử.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // ==================== LỊCH SỬ NHẬP HÀNG ====================
    public function lichsu()
    {
        $nhapHangs = NhapHang::with(['chiTietNhapHangs.sanPham', 'nhaKho'])
                        ->latest('idNhapHang')
                        ->paginate(10);

        return view('admin.kho.lichsu', compact('nhapHangs'));
    }
}