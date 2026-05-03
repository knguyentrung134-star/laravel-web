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
        Schema::create('trahang', function (Blueprint $table) {
    $table->id('idTraHang');

    $table->date('ngayTra')->nullable();
    $table->string('lyDo', 200)->nullable();

    $table->unsignedBigInteger('idDonHang')->nullable();

    $table->foreign('idDonHang')->references('idDonHang')->on('donhang');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trahang');
    }
};
