<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GioHang extends Model
{
    protected $table = 'giohang';
    protected $primaryKey = 'idGioHang';
    public $timestamps = false;

    protected $fillable = [
        'ngayTao', 
        'idKhachHang'
    ];

    protected $casts = [
        'ngayTao' => 'datetime',
    ];

    /**
     * Relationship với Khách hàng
     */
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'idKhachHang', 'idKhachHang');
    }

    /**
     * Relationship với các sản phẩm trong giỏ hàng
     */
    public function donTrongGioHangs()
    {
        return $this->hasMany(DonTrongGioHang::class, 'idGioHang', 'idGioHang');
    }

    /**
     * Scope: Lấy giỏ hàng theo khách hàng
     */
    public function scopeOfCustomer($query, $idKhachHang)
    {
        return $query->where('idKhachHang', $idKhachHang);
    }

    /**
     * Kiểm tra giỏ hàng có trống không
     */
    public function isEmpty(): bool
    {
        return $this->donTrongGioHangs()->count() === 0;
    }

    /**
     * Tính tổng số lượng sản phẩm trong giỏ (không tính giá)
     */
    public function getTotalQuantityAttribute()
    {
        return $this->donTrongGioHangs()->sum('soLuong');
    }

    /**
     * Tạo giỏ hàng mới với ngày tạo mặc định
     */
    public static function createForCustomer($idKhachHang)
    {
        return self::create([
            'idKhachHang' => $idKhachHang,
            'ngayTao'     => Carbon::now(),
        ]);
    }
}