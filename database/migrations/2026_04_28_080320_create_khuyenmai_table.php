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
        Schema::create('khuyenmai', function (Blueprint $table) {
    $table->id('idKhuyenMai');
    $table->string('moTaKhuyenMai', 200)->nullable();
    $table->date('ngayBatDau')->nullable();
    $table->date('ngayKetThuc')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khuyenmai');
    }
};
