<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietDonHang extends Model
{
    protected $table = 'chitietdonhang';
    protected $primaryKey = 'idChiTietDonHang';
    public $timestamps = false;

    protected $fillable = [
        'soLuong', 
        'donGia', 
        'idDonHang', 
        'idSanPham'
    ];

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'idSanPham', 'idSanPham');
    }

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'idDonHang', 'idDonHang');
    }
}