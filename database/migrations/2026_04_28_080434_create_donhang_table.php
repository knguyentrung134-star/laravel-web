<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donhang', function (Blueprint $table) {
    $table->id('idDonHang');
    $table->date('ngayLap')->nullable();
    $table->double('tongThanhTien')->nullable();
    $table->double('giamGia')->default(0);
    $table->string('trangThai', 50)->nullable();

    $table->unsignedBigInteger('idNguoiDung')->nullable();
    $table->unsignedBigInteger('idKhachHang')->nullable();

    $table->foreign('idNguoiDung')->references('idNguoiDung')->on('nguoidung');
    $table->foreign('idKhachHang')->references('idKhachHang')->on('khachhang');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donhang');
    }
};
