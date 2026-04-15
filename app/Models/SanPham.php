<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    protected $table = 'sanpham';
    protected $primaryKey = 'idSanPham';
    public $timestamps = false;

    protected $fillable = ['tenSanPham', 'moTa', 'soLuong', 'gia', 'hinh_anh', 'trangThai'];

    public function hinhAnhs()
    {
        return $this->hasMany(HinhAnh::class, 'idSanPham', 'idSanPham');
    }

    public function hangTonKhos()
    {
        return $this->hasMany(HangTonKho::class, 'idSanPham', 'idSanPham');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'idSanPham', 'idSanPham');
    }

    public function chiTietNhapHangs()
    {
        return $this->hasMany(ChiTietNhapHang::class, 'idSanPham', 'idSanPham');
    }

    public function donTrongGioHangs()
    {
        return $this->hasMany(DonTrongGioHang::class, 'idSanPham', 'idSanPham');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGiaSanPham::class, 'idSanPham', 'idSanPham');
    }

    public function sanPhamKhuyenMais()
    {
        return $this->hasMany(SanPhamKhuyenMai::class, 'idSanPham', 'idSanPham');
    }
}