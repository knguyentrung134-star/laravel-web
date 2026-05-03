<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    protected $table = 'donhang';
    protected $primaryKey = 'idDonHang';
    public $timestamps = false;

    // 🔥 thêm giamGia (không đổi logic)
    protected $fillable = ['ngayLap', 'tongThanhTien', 'giamGia', 'trangThai', 'idNguoiDung', 'idKhachHang'];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'idNguoiDung', 'idNguoiDung');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'idKhachHang', 'idKhachHang');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'idDonHang', 'idDonHang');
    }

    public function thanhToans()
    {
        return $this->hasMany(ThanhToan::class, 'idDonHang', 'idDonHang');
    }

    public function traHangs()
    {
        return $this->hasOne(TraHang::class, 'idDonHang', 'idDonHang');
    }

    public function donTrongGioHangs()
    {
        return $this->hasMany(DonTrongGioHang::class, 'idDonHang', 'idDonHang');
    }
}