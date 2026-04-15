<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;

class HashOldPasswords extends Command
{
    protected $signature = 'passwords:hash-old';
    protected $description = 'Hash tất cả mật khẩu đang là plain text sang bcrypt';

    public function handle()
    {
        $users = NguoiDung::whereRaw('LENGTH(matKhau) < 60 OR matKhau NOT LIKE "$2%"')->get();

        if ($users->isEmpty()) {
            $this->info('✅ Không có mật khẩu plain text nào cần hash!');
            return;
        }

        $count = 0;
        foreach ($users as $user) {
            // Chỉ hash nếu chưa phải bcrypt
            if (!Hash::check($user->matKhau, $user->matKhau)) {
                $user->matKhau = Hash::make($user->matKhau);
                $user->save();
                $count++;
                $this->info("✓ Hashed user: {$user->tenNguoiDung}");
            }
        }

        $this->info("🎉 Đã hash thành công {$count} mật khẩu!");
    }
}