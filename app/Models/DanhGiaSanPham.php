<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGiaSanPham extends Model
{
    protected $table = 'danhgiasanpham';
    protected $primaryKey = 'idDanhGia';
    public $timestamps = false;

    protected $fillable = ['noiDung', 'soSao', 'idNguoiDung', 'idSanPham'];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'idNguoiDung', 'idNguoiDung');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'idSanPham', 'idSanPham');
    }
}