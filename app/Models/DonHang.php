<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'idKhachHang'
    ];

    // ==================== RELATIONSHIPS ====================

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'idKhachHang', 'idKhachHang');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'idDonHang', 'idDonHang');
    }

    public function thanhToan()
    {
        return $this->hasOne(ThanhToan::class, 'idDonHang', 'idDonHang');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'idNguoiDung', 'idNguoiDung');
    }

    // Helper method
    public function canCancel()
    {
        return in_array($this->trangThai, ['Đang xử lý', 'Chờ xác nhận']);
    }
}