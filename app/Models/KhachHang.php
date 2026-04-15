<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    protected $table = 'khachhang';
    protected $primaryKey = 'idKhachHang';
    public $timestamps = false;

    protected $fillable = ['tenKhachHang', 'diaChi', 'soDienThoai', 'idNguoiDung'];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'idNguoiDung', 'idNguoiDung');
    }

    public function gioHangs()
    {
        return $this->hasMany(GioHang::class, 'idKhachHang', 'idKhachHang');
    }

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'idKhachHang', 'idKhachHang');
    }
}