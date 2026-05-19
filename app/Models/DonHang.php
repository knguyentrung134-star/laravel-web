<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ChiTietDonHang; // 👈 THÊM DÒNG NÀY

class DonHang extends Model
{
    protected $table = 'donhang';

    protected $primaryKey = 'idDonHang';

    public $timestamps = false;

    protected $fillable = [
        'ngayLap',
        'tongThanhTien',
        'giamGia',
        'trangThai',
        'idNguoiDung',
        'idKhachHang',
        'lyDoHuy',
        'ngayHuy'
    ];

    // ====================== RELATIONSHIPS ======================

    public function nguoiDung()
    {
        return $this->belongsTo(
            NguoiDung::class,
            'idNguoiDung',
            'idNguoiDung'
        );
    }

    public function khachHang()
    {
        return $this->belongsTo(
            KhachHang::class,
            'idKhachHang',
            'idKhachHang'
        );
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(
            ChiTietDonHang::class,
            'idDonHang',
            'idDonHang'
        );
    }

    public function thanhToans()
    {
        return $this->hasMany(
            ThanhToan::class,
            'idDonHang',
            'idDonHang'
        );
    }

    public function traHangs()
    {
        return $this->hasOne(
            TraHang::class,
            'idDonHang',
            'idDonHang'
        );
    }

    // ====================== HỦY ĐƠN HÀNG ======================

    public function canCancel()
    {
        
        return !in_array($this->trangThai, [
            'Hoàn thành',
            'Đã hủy',
            'da_huy'
        ]);
    }
    public function cancel($lyDo = null)
{
    if (in_array($this->trangThai, ['da_huy', 'Đã hủy'])) {
        return;
    }

    DB::transaction(function () use ($lyDo) {

        $chiTiets = ChiTietDonHang::where('idDonHang', $this->idDonHang)
            ->with('sanPham')
            ->get();

        foreach ($chiTiets as $chiTiet) {

            if ($chiTiet->sanPham) {
                $chiTiet->sanPham->increment('soLuong', $chiTiet->soLuong);
            }
        }

        $this->update([
            'trangThai' => 'da_huy',
            'lyDoHuy'   => $lyDo,
            'ngayHuy'   => now()
        ]);
    });
}
}