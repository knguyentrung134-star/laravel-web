<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonHangController extends Controller
{
    public function index()
    {
        $donHangs = DonHang::with(['khachHang', 'chiTietDonHangs.sanPham'])
                    ->latest('idDonHang')
                    ->paginate(10);

        return view('admin.donhang.index', compact('donHangs'));
    }

    public function show(DonHang $donhang)
    {
        $donhang->load(['khachHang', 'chiTietDonHangs.sanPham', 'thanhToans']);
        return view('admin.donhang.show', compact('donhang'));  
    }
    public function update(Request $request, DonHang $donhang)
{
    $request->validate([
        'trangThai' => 'required|in:Đang xử lý,Đã xác nhận,Đang giao,Hoàn thành,Đã hủy',
    ]);

    try {
        DB::beginTransaction();

        $oldStatus = $donhang->trangThai;
        $newStatus = $request->trangThai;

        $donhang->update(['trangThai' => $newStatus]);

        if ($newStatus === 'Đã hủy' && $oldStatus !== 'Đã hủy') {
            $this->restoreStock($donhang);
        }

        DB::commit();

        return redirect()->route('admin.donhang.index')
            ->with('success', '✅ Cập nhật trạng thái đơn hàng thành công!');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Lỗi cập nhật đơn hàng: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
    }
}
private function restoreStock(DonHang $donhang)
{
    // Load đầy đủ relation
    $donhang->load('chiTietDonHangs.sanPham.tonKho');

    \Log::info("=== BẮT ĐẦU HOÀN KHO ĐƠN #{$donhang->idDonHang} ===");

    foreach ($donhang->chiTietDonHangs as $chiTiet) {
        $sanPham = $chiTiet->sanPham;
        if (!$sanPham) {
            \Log::warning("Không tìm thấy sản phẩm trong chi tiết đơn");
            continue;
        }

        $soLuongHoan = (int) $chiTiet->soLuong;
        if ($soLuongHoan <= 0) continue;

        try {
            // 1. Cập nhật cột soLuong trong bảng sanpham
            $sanPham->increment('soLuong', $soLuongHoan);

            // 2. Cập nhật bảng hangtonkho (bảng chính đang dùng ở trang kho)
            if ($sanPham->tonKho) {
                $sanPham->tonKho->increment('soLuong', $soLuongHoan);
                
                \Log::info("✅ HOÀN KHO THÀNH CÔNG | SP: {$sanPham->idSanPham} - {$sanPham->tenSanPham} | +{$soLuongHoan} | Tồn mới: {$sanPham->tonKho->fresh()->soLuong}");
            } else {
                // Trường hợp chưa có bản ghi tồn kho
                \App\Models\HangTonKho::create([
                    'idSanPham' => $sanPham->idSanPham,
                    'idNhaKho'  => 1,
                    'soLuong'   => $soLuongHoan
                ]);
                \Log::info("Tạo mới bản ghi HangTonKho cho SP {$sanPham->idSanPham}");
            }
        } catch (\Exception $e) {
            \Log::error("❌ Lỗi hoàn kho SP {$sanPham->idSanPham}: " . $e->getMessage());
        }
    }
}
}