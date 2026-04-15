<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraHang extends Model
{
    protected $table = 'trahang';
    protected $primaryKey = 'idTraHang';
    public $timestamps = false;

    protected $fillable = ['ngayTra', 'lyDo', 'idDonHang'];

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'idDonHang', 'idDonHang');
    }
}