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
        Schema::create('nguoidung', function (Blueprint $table) {
    $table->id('idNguoiDung');
    $table->string('tenNguoiDung', 100)->nullable();
    $table->string('matKhau', 100)->nullable();
    $table->string('vaiTro', 50)->nullable();
    $table->string('email', 100)->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguoidung');
    }
};
