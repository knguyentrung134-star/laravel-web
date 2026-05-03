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
        Schema::create('sanpham', function (Blueprint $table) {
    $table->id('idSanPham');
    $table->string('tenSanPham', 100);
    $table->string('moTa', 300)->nullable();
    $table->string('theLoai', 100)->nullable();
    $table->integer('soLuong')->default(10);
    $table->double('gia');
    $table->string('hinh_anh')->nullable();
    $table->string('trangThai', 50)->default('Còn hàng');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanpham');
    }
};
