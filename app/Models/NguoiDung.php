<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class NguoiDung extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'nguoidung';
    protected $primaryKey = 'idNguoiDung';
    public $timestamps = false;

    // Những field cho phép mass assignment
    protected $fillable = [
        'ten_dang_nhap', 
        'tenNguoiDung', 
        'email', 
        'mat_khau', 
        'matKhau', 
        'ho_ten', 
        'hoTen', 
        'so_dien_thoai', 
        'vai_tro', 
        'vaiTro'
    ];

    protected $hidden = ['mat_khau', 'matKhau'];

    // Custom password column
    public function getAuthPasswordName()
    {
        return 'mat_khau';     // ưu tiên tên này
    }

    public function getAuthPassword()
    {
        return $this->mat_khau ?? $this->matKhau;
    }

    // Relationship
    public function khachHang()
    {
        return $this->hasOne(\App\Models\KhachHang::class, 'idNguoiDung', 'idNguoiDung');
    }

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'idKhachHang', 'idNguoiDung');
    }
}