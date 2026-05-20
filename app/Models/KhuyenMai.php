<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhuyenMai extends Model
{
    protected $table = 'khuyenmai';
    protected $primaryKey = 'idKhuyenMai';
    public $timestamps = false;   // Không dùng created_at, updated_at

    /**
     * Các cột được phép gán hàng loạt (mass assignment)
     */
    protected $fillable = [
        'maKhuyenMai',
        'tenKhuyenMai',
        'moTaKhuyenMai',
        'phanTramGiam',
        'ngayBatDau',
        'ngayKetThuc',
        'trangThai'
    ];

    /**
     * Cast dữ liệu cho một số cột
     */
    protected $casts = [
        'phanTramGiam' => 'integer',
        'trangThai'    => 'boolean',
        'ngayBatDau'   => 'date',
        'ngayKetThuc'  => 'date',
    ];

    /**
     * Scope mặc định: sắp xếp theo ID giảm dần
     */
    protected static function booted()
    {
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('idKhuyenMai', 'desc');
        });
    }

    /**
     * Kiểm tra khuyến mãi còn hiệu lực không
     */
    public function isActive(): bool
    {
        $today = now()->toDateString();
        return $this->trangThai == 1 
               && $this->ngayBatDau <= $today 
               && $this->ngayKetThuc >= $today;
    }
}