<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dontronggiohang', function (Blueprint $table) {
            $table->decimal('gia', 15, 0)->nullable()->after('soLuong');
        });
    }

    public function down(): void
    {
        Schema::table('dontronggiohang', function (Blueprint $table) {
            $table->dropColumn('gia');
        });
    }
};