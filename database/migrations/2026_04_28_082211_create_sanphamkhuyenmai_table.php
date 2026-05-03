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
        Schema::create('sanphamkhuyenmai', function (Blueprint $table) {
    $table->id('idSPKM');

    $table->double('giaKhuyenMai')->nullable();

    $table->unsignedBigInteger('idSanPham');
    $table->unsignedBigInteger('idKhuyenMai');

    $table->foreign('idSanPham')->references('idSanPham')->on('sanpham')->onDelete('cascade');
    $table->foreign('idKhuyenMai')->references('idKhuyenMai')->on('khuyenmai')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanphamkhuyenmai');
    }
};
