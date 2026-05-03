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
        Schema::create('hinh_anh', function (Blueprint $table) {
    $table->id('idHinhAnh');

    $table->unsignedBigInteger('idSanPham')->nullable();
    $table->string('duongDan')->nullable();
    $table->boolean('laChinh')->default(false);

    $table->foreign('idSanPham')->references('idSanPham')->on('sanpham')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hinh_anh');
    }
};
