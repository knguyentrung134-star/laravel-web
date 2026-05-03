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
        $hangTonKhos = SanPham::leftJoin('hangtonkho', 'sanpham.idSanPham', '=', 'hangtonkho.idSanPham')
            ->select(
                'sanpham.idSanPham',
                'sanpham.tenSanPham',
                'sanpham.theLoai',
                'sanpham.gia',
                DB::raw('COALESCE(SUM(hangtonkho.soLuong),0) as soLuong')
            )
            ->groupBy('sanpham.idSanPham','sanpham.tenSanPham','sanpham.theLoai','sanpham.gia')
            ->paginate(12);

        return view('admin.kho.index', compact('hangTonKhos'));
    }

    public function create()
    {
        $sanPhams = SanPham::orderBy('tenSanPham')->get();
        return view('admin.kho.create', compact('sanPhams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idSanPham'   => 'required|exists:sanpham,idSanPham',
            'soLuongNhap' => 'required|integer|min:1',
            'donGiaNhap'  => 'required|numeric|min:0',
            'ghiChu'      => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            $nhapHang = NhapHang::create([
                'ngayNhap'  => now(),
                'trangThai' => 'Đã nhập',
                'idNhaKho'  => 1,
                'ghiChu'    => $request->ghiChu
            ]);

            ChiTietNhapHang::create([
                'soLuong'       => $request->soLuongNhap,
                'donGiaNhap'    => $request->donGiaNhap,
                'idNhapHang'    => $nhapHang->idNhapHang,
                'idSanPham'     => $request->idSanPham,
            ]);

            // Cập nhật tồn kho
            $tonKho = HangTonKho::firstOrCreate(
                ['idSanPham' => $request->idSanPham, 'idNhaKho' => 1],
                ['soLuong' => 0]
            );

            $tonKho->increment('soLuong', $request->soLuongNhap);

            // Đồng bộ với bảng sanpham
            SanPham::where('idSanPham', $request->idSanPham)
                ->update([
                    'soLuong' => DB::raw('COALESCE(soLuong, 0) + ' . $request->soLuongNhap)
                ]);

            DB::commit();

            // ✅ FIX CHÍNH Ở ĐÂY
            return redirect()
                ->route('admin.kho.index')
                ->with('success', '✅ Nhập hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', '❌ Lỗi: ' . $e->getMessage());
        }
    }

    public function lichsu()
    {
        $nhapHangs = NhapHang::with(['chiTietNhapHangs.sanPham'])
            ->latest('idNhapHang')
            ->paginate(10);

        return view('admin.kho.lichsu', compact('nhapHangs'));
    }
}