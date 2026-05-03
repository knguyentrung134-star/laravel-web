<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(NguoidungTableSeeder::class);
        $this->call(KhachhangTableSeeder::class);
        $this->call(SanphamTableSeeder::class);
        $this->call(DonhangTableSeeder::class);
        $this->call(ChitietdonhangTableSeeder::class);
        $this->call(ThanhtoanTableSeeder::class);
        $this->call(TrahangTableSeeder::class);
        $this->call(HangtonkhoTableSeeder::class);
        $this->call(HinhAnhTableSeeder::class); 
        $this->call(DanhgiasanphamTableSeeder::class);
        $this->call(SanphamkhuyenmaiTableSeeder::class);
        $this->call(ChitietnhaphangTableSeeder::class);
        $this->call(KhuyenmaiTableSeeder::class);
        $this->call(NhaphangTableSeeder::class);
        $this->call(DontronggiohangTableSeeder::class);
        $this->call(GiohangTableSeeder::class);
        $this->call(ChuongtrinhGiamgiaTableSeeder::class);
    }
}
