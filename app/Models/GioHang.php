<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GioHang extends Model
{
    protected $table = 'giohang';
    protected $primaryKey = 'idGioHang';
    public $timestamps = false;

    protected $fillable = ['ngayTao', 'idKhachHang'];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'idKhachHang', 'idKhachHang');
    }

    public function donTrongGioHangs()
    {
        return $this->hasMany(DonTrongGioHang::class, 'idGioHang', 'idGioHang');
    }
}