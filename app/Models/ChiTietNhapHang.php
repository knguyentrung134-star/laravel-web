<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietNhapHang extends Model
{
    protected $table = 'chitietnhaphang';
    protected $primaryKey = 'idChiTietNH';
    public $timestamps = false;

    protected $fillable = ['soLuong', 'donGiaNhap', 'idNhapHang', 'idSanPham'];

    public function nhapHang()
    {
        return $this->belongsTo(NhapHang::class, 'idNhapHang', 'idNhapHang');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'idSanPham', 'idSanPham');
    }
}