<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\NguoiDung;

class LoginController extends Controller
{
    /**
     * Hiển thị form đăng nhập (GET /login)
     */
    public function showLoginForm()
    {
        // Nếu view của bạn là resources/views/auth/login.blade.php
        return view('auth.login');

        // Nếu view của bạn tên khác (ví dụ: login.blade.php) thì đổi thành:
        // return view('login');
    }

    /**
     * Xử lý đăng nhập (POST /login)
     */
    public function login(Request $request)
    {
        $request->validate([
            'tenNguoiDung' => 'required|string',
            'matKhau'      => 'required|string',
        ], [
            'tenNguoiDung.required' => 'Vui lòng nhập tên đăng nhập',
            'matKhau.required'      => 'Vui lòng nhập mật khẩu',
        ]);

        $user = NguoiDung::where('tenNguoiDung', $request->tenNguoiDung)->first();

        if (!$user) {
            return back()
                ->withErrors(['tenNguoiDung' => 'Tên đăng nhập không tồn tại'])
                ->withInput();
        }

        $plainPassword = $request->matKhau;

        // 1. Kiểm tra mật khẩu plain text (dữ liệu cũ)
        if ($user->matKhau === $plainPassword) {
            $user->update(['matKhau' => Hash::make($plainPassword)]);

            Auth::login($user);
            $request->session()->regenerate();

            return $user->vaiTro === 'Admin' 
                ? redirect()->intended('/admin/dashboard') 
                : redirect()->intended('/');
        }

        // 2. Kiểm tra bcrypt hash
        if (Hash::check($plainPassword, $user->matKhau)) {
            Auth::login($user);
            $request->session()->regenerate();

            return $user->vaiTro === 'Admin' 
                ? redirect()->intended('/admin/dashboard') 
                : redirect()->intended('/');
        }

        return back()
            ->withErrors(['matKhau' => 'Mật khẩu không đúng'])
            ->withInput();
    }

    /**
     * Đăng xuất (POST /logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
        session(['show_welcome' => true]);
    }
}