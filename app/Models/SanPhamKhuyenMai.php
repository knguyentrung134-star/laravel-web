<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPhamKhuyenMai extends Model
{
    protected $table = 'sanphamkhuyenmai';
    protected $primaryKey = 'idSPKM';
    public $timestamps = false;

    protected $fillable = ['giaKhuyenMai', 'idSanPham', 'idKhuyenMai'];

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'idSanPham', 'idSanPham');
    }

    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'idKhuyenMai', 'idKhuyenMai');
    }
}