<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonTrongGioHang extends Model
{
    protected $table = 'dontronggiohang';
    protected $primaryKey = 'idDonTrongGioHang';
    public $timestamps = false;

    protected $fillable = ['soLuong', 'idGioHang', 'idSanPham', 'idDonHang'];

    public function gioHang()
    {
        return $this->belongsTo(GioHang::class, 'idGioHang', 'idGioHang');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'idSanPham', 'idSanPham');
    }

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'idDonHang', 'idDonHang');
    }
}