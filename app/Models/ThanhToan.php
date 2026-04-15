<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    protected $table = 'thanhtoan';
    protected $primaryKey = 'idThanhToan';
    public $timestamps = false;

    protected $fillable = ['idDonHang', 'ngayThanhToan', 'soTien', 'phuongThuc', 'trangThai'];

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'idDonHang', 'idDonHang');
    }
}