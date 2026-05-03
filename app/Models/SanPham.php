<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    protected $table = 'sanpham';
    protected $primaryKey = 'idSanPham';
    public $timestamps = false;

    protected $fillable = [
        'tenSanPham',
        'moTa',
        'theLoai',
        'gia',
        'hinh_anh',
        'trangThai',
        'soLuong'
    ];

    // Relationship
    public function tonKho()
    {
        return $this->hasOne(HangTonKho::class, 'idSanPham', 'idSanPham');
    }

    public function donTrongGioHangs()
    {
        return $this->hasMany(DonTrongGioHang::class, 'idSanPham', 'idSanPham');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGiaSanPham::class, 'idSanPham', 'idSanPham');
    }

    // Tự động tạo tồn kho khi tạo sản phẩm mới
    protected static function booted()
    {
        static::created(function ($sanPham) {
            HangTonKho::firstOrCreate(
                ['idSanPham' => $sanPham->idSanPham, 'idNhaKho' => 1],
                ['soLuong' => $sanPham->soLuong ?? 0]
            );
        });
    }

    // Lấy tồn kho thực tế từ bảng hangtonkho
    public function getSoLuongThucTeAttribute()
    {
        return $this->tonKho?->soLuong ?? 0;
    }
}