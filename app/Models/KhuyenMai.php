<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhuyenMai extends Model
{
    protected $table = 'khuyenmai';
    protected $primaryKey = 'idKhuyenMai';
    public $timestamps = false;     // ← Quan trọng: tắt timestamps

    protected $fillable = ['moTaKhuyenMai', 'ngayBatDau', 'ngayKetThuc'];

    // Sắp xếp mặc định theo id giảm dần (vì không có created_at)
    protected $orderBy = 'idKhuyenMai';
    protected $orderDirection = 'desc';
}