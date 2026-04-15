<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use Notifiable;

    protected $table = 'nguoidung';
    protected $primaryKey = 'idNguoiDung';
    public $timestamps = false;

    protected $fillable = ['tenNguoiDung', 'matKhau', 'vaiTro', 'email'];

    // Laravel dùng cột 'password' mặc định → đổi sang 'matKhau'
    protected $hidden = ['matKhau'];

    public function getAuthPasswordName()
    {
        return 'matKhau';
    }
    public function khachHang()
{
    return $this->hasOne(\App\Models\KhachHang::class, 'idNguoiDung', 'idNguoiDung');
}

    public function getAuthPassword()
    {
        return $this->matKhau;
    }

    // Relationship
    // public function khachHang()
    // {
    //     return $this->hasOne(KhachHang::class, 'idNguoiDung', 'idNguoiDung');
    // }

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'idNguoiDung', 'idNguoiDung');
    }
}