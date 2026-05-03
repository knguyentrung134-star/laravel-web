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
        Schema::create('giohang', function (Blueprint $table) {
    $table->id('idGioHang');
    $table->date('ngayTao')->nullable();

    $table->unsignedBigInteger('idKhachHang')->nullable();

    $table->foreign('idKhachHang')
        ->references('idKhachHang')->on('khachhang')
        ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giohang');
    }
};
