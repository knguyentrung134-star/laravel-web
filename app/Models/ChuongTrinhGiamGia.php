<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ChuongTrinhGiamGia extends Model
{
    protected $table = 'chuongtrinh_giamgia';
    protected $primaryKey = 'idGiamGia';
    public $timestamps = false;

    protected $fillable = [
        'tenChuongTrinh',
        'theLoai',
        'phanTramGiam',
        'ngayBatDau',
        'ngayKetThuc',
    ];

    /**
     * Cast các trường ngày tháng
     */
    protected $casts = [
        'ngayBatDau'   => 'datetime',
        'ngayKetThuc'  => 'datetime',
        'phanTramGiam' => 'decimal:2',
    ];

    /**
     * Scope: Lấy các chương trình giảm giá đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('ngayBatDau', '<=', Carbon::now())
                     ->where('ngayKetThuc', '>=', Carbon::now());
    }

    /**
     * Scope: Lấy giảm giá theo thể loại
     */
    public function scopeByCategory($query, $theLoai)
    {
        return $query->where('theLoai', $theLoai);
    }

    /**
     * Kiểm tra chương trình giảm giá có đang hoạt động không
     */
    public function isActive(): bool
    {
        $now = Carbon::now();
        return $this->ngayBatDau <= $now && $this->ngayKetThuc >= $now;
    }

    /**
     * Tính giá sau khi giảm
     */
    public function calculateDiscountedPrice($giaGoc)
    {
        if (!$this->isActive() || $this->phanTramGiam <= 0) {
            return $giaGoc;
        }

        return $giaGoc * (1 - $this->phanTramGiam / 100);
    }
}