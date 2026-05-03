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
        Schema::create('chitietdonhang', function (Blueprint $table) {
    $table->id('idChiTietDH');

    $table->integer('soLuong')->nullable();
    $table->double('donGia')->nullable();

    $table->unsignedBigInteger('idDonHang')->nullable();
    $table->unsignedBigInteger('idSanPham')->nullable();

    $table->foreign('idDonHang')->references('idDonHang')->on('donhang')->onDelete('cascade');
    $table->foreign('idSanPham')->references('idSanPham')->on('sanpham')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chitietdonhang');
    }
};
