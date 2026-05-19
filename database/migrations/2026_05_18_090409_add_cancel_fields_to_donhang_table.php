<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('donhang', function (Blueprint $table) {
        $table->text('lyDoHuy')->nullable()->after('trangThai');
        $table->timestamp('ngayHuy')->nullable()->after('lyDoHuy');
    });
}
};
