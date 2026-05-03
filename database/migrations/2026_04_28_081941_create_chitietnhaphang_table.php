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
        Schema::create('chitietnhaphang', function (Blueprint $table) {
    $table->id('idChiTietNH');

    $table->integer('soLuong')->nullable();
    $table->double('donGiaNhap')->nullable();

    $table->unsignedBigInteger('idNhapHang')->nullable();
    $table->unsignedBigInteger('idSanPham')->nullable();

    $table->foreign('idNhapHang')->references('idNhapHang')->on('nhaphang')->onDelete('cascade');
    $table->foreign('idSanPham')->references('idSanPham')->on('sanpham')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chitietnhaphang');
    }
};
