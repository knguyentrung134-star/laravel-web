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
       Schema::create('nhaphang', function (Blueprint $table) {
    $table->id('idNhapHang');
    $table->date('ngayNhap')->nullable();
    $table->string('trangThai', 50)->nullable();

    $table->unsignedBigInteger('idNhaKho')->nullable();

    $table->foreign('idNhaKho')
        ->references('idNhaKho')->on('nhakho')
        ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhaphang');
    }
};
