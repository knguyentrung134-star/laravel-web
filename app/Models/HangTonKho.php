<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HangTonKho extends Model
{
    protected $table = 'hangtonkho';
    protected $primaryKey = 'idHangTonKho';
    public $timestamps = false;

    protected $fillable = [
        'idSanPham',
        'idNhaKho',
        'soLuong'
    ];

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'idSanPham', 'idSanPham');
    }

    public function nhaKho()
    {
        return $this->belongsTo(NhaKho::class, 'idNhaKho', 'idNhaKho');
    }

    public function scopeOfKho($query, $idNhaKho = 1)
    {
        return $query->where('idNhaKho', $idNhaKho);
    }
}