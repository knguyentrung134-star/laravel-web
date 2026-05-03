<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;

class NguoidungTableSeeder extends Seeder
{
    public function run()
    {
        NguoiDung::updateOrCreate(
            ['email' => 'admin@gmail.com'], // điều kiện
            [
                'tenNguoiDung' => 'admin1',
                'matKhau' => Hash::make('123456'),
                'vaiTro' => 'Admin'
            ]
        );
    }
}