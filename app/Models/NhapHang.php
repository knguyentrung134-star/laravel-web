<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhapHang extends Model
{
    protected $table = 'nhaphang';
    protected $primaryKey = 'idNhapHang';
    public $timestamps = false;

    protected $fillable = ['ngayNhap', 'trangThai', 'idNhaKho'];

    public function nhaKho()
    {
        return $this->belongsTo(NhaKho::class, 'idNhaKho', 'idNhaKho');
    }

    public function chiTietNhapHangs()
    {
        return $this->hasMany(ChiTietNhapHang::class, 'idNhapHang', 'idNhapHang');
    }
}