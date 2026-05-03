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
       Schema::create('khachhang', function (Blueprint $table) {
    $table->id('idKhachHang');
    $table->string('tenKhachHang', 100)->nullable();
    $table->string('diaChi', 200)->nullable();
    $table->string('soDienThoai', 20)->nullable();

    $table->unsignedBigInteger('idNguoiDung')->nullable();

    $table->foreign('idNguoiDung')
        ->references('idNguoiDung')->on('nguoidung')
        ->onDelete('cascade');
});
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khachhang');
    }
};
