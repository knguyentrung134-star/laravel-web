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
        Schema::create('thanhtoan', function (Blueprint $table) {
    $table->id('idThanhToan');

    $table->unsignedBigInteger('idDonHang')->nullable();

    $table->date('ngayThanhToan')->nullable();
    $table->double('soTien')->nullable();

    $table->enum('phuongThuc', [
        'TienMat','ChuyenKhoan','TheTinDung','ViDienTu'
    ])->nullable();

    $table->string('trangThai', 50)->nullable();

    $table->foreign('idDonHang')->references('idDonHang')->on('donhang');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thanhtoan');
    }
};
