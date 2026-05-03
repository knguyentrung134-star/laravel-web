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
        Schema::create('chuongtrinh_giamgia', function (Blueprint $table) {
    $table->id('idGiamGia');

    $table->string('tenChuongTrinh')->nullable();
    $table->string('theLoai', 100)->nullable();
    $table->integer('phanTramGiam')->nullable();

    $table->date('ngayBatDau')->nullable();
    $table->date('ngayKetThuc')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chuongtrinh_giamgia');
    }
};
