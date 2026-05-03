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
        Schema::create('hangtonkho', function (Blueprint $table) {
    $table->id('idHangTonKho');

    $table->integer('soLuong')->default(0);

    $table->unsignedBigInteger('idSanPham')->nullable();
    $table->unsignedBigInteger('idNhaKho')->nullable();

    $table->foreign('idSanPham')->references('idSanPham')->on('sanpham')->onDelete('cascade');
    $table->foreign('idNhaKho')->references('idNhaKho')->on('nhakho')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hangtonkho');
    }
};
