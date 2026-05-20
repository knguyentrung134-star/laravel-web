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
        Schema::table('khuyenmai', function (Blueprint $table) {

            $table->string('maKhuyenMai')->nullable();

            $table->integer('phanTramGiam')->nullable();

            $table->boolean('trangThai')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khuyenmai', function (Blueprint $table) {

            $table->dropColumn([
                'maKhuyenMai',
                'phanTramGiam',
                'trangThai'
            ]);
        });
    }
};