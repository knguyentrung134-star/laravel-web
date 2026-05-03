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
        Schema::create('dontronggiohang', function (Blueprint $table) {
    $table->id('idDonTrongGioHang');

    $table->unsignedBigInteger('idGioHang');
    $table->unsignedBigInteger('idSanPham');

    $table->integer('soLuong')->default(1);

    $table->timestamps();

    $table->unique(['idGioHang', 'idSanPham']);

    $table->foreign('idGioHang')->references('idGioHang')->on('giohang')->onDelete('cascade');
    $table->foreign('idSanPham')->references('idSanPham')->on('sanpham')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dontronggiohang');
    }
};
