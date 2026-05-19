<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Lấy danh sách đơn hàng của user
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = DonHang::where('idKhachHang', $user->idNguoiDung)
            ->with('chiTietDonHangs.sanPham')
            ->latest()
            ->paginate(10);

        return response()->json($orders, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Chi tiết một đơn hàng
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $order = DonHang::with('chiTietDonHangs.sanPham')
            ->where('idKhachHang', $user->idNguoiDung)
            ->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Không tìm thấy đơn hàng'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($order, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Tạo đơn hàng mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'hoTen'               => 'required|string',
            'diaChiGiaoHang'      => 'required|string',
            'soDienThoai'         => 'required|string',
            'phuongThucThanhToan' => 'required|in:cash,card,transfer',
            'ghiChu'              => 'nullable|string',

            'products'             => 'required|array|min:1',
            'products.*.idSanPham' => 'required|integer|exists:sanpham,idSanPham',
            'products.*.soLuong'   => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $user = $request->user();
            $tongTien = 0;

            foreach ($request->products as $item) {

                $sanPham = SanPham::findOrFail($item['idSanPham']);

                if ($sanPham->soLuong < $item['soLuong']) {

                    return response()->json([
                        'message' => "Sản phẩm {$sanPham->tenSanPham} không đủ tồn kho"
                    ], 400);
                }

                $tongTien += $sanPham->gia * $item['soLuong'];
            }

            // Tạo đơn hàng
            $order = DonHang::create([
                'idKhachHang'         => $user->idNguoiDung,
                'tongThanhTien'       => $tongTien,
                'trangThai'           => 'Cho_xac_nhan',
            ]);

            // Tạo chi tiết đơn hàng
            foreach ($request->products as $item) {

                $sanPham = SanPham::findOrFail($item['idSanPham']);

                ChiTietDonHang::create([
                    'idDonHang' => $order->idDonHang,
                    'idSanPham' => $sanPham->idSanPham,
                    'soLuong'   => $item['soLuong'],
                    'donGia'    => $sanPham->gia,
                ]);

                // Trừ kho
                $sanPham->decrement('soLuong', $item['soLuong']);
            }

            DB::commit();

            $order->load('chiTietDonHangs.sanPham');

            return response()->json([
                'message'  => 'Đặt hàng thành công!',
                'order_id' => $order->idDonHang,
                'order'    => $order
            ], 201, [], JSON_UNESCAPED_UNICODE);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Đặt hàng thất bại',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}