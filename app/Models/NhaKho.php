<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaKho extends Model
{
    protected $table = 'nhakho';
    protected $primaryKey = 'idNhaKho';
    public $timestamps = false;

    protected $fillable = [
        'tenNhaKho', 
        'diaChi', 
        'trangThai'
    ];

    public function hangTonKhos()
    {
        return $this->hasMany(HangTonKho::class, 'idNhaKho', 'idNhaKho');
    }

    public function nhapHangs()
    {
        return $this->hasMany(NhapHang::class, 'idNhaKho', 'idNhaKho');
    }
}