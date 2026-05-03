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
       Schema::create('danhgiasanpham', function (Blueprint $table) {
    $table->id('idDanhGia');

    $table->string('noiDung', 200)->nullable();
    $table->integer('soSao')->nullable();

    $table->unsignedBigInteger('idNguoiDung')->nullable();
    $table->unsignedBigInteger('idSanPham')->nullable();

    $table->foreign('idNguoiDung')->references('idNguoiDung')->on('nguoidung')->onDelete('cascade');
    $table->foreign('idSanPham')->references('idSanPham')->on('sanpham')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danhgiasanpham');
    }
};
