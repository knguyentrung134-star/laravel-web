<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HinhAnh extends Model
{
    protected $table = 'hinh_anh';
    protected $primaryKey = 'idHinhAnh';
    public $timestamps = false;

    protected $fillable = ['idSanPham', 'duongDan', 'laChinh'];

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'idSanPham', 'idSanPham');
    }
}