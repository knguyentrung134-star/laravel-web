<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'tenNguoiDung' => 'required|unique:nguoidung',
            'email' => 'required|email|unique:nguoidung',
            'matKhau' => 'required|min:6|confirmed',
            'vaiTro' => 'required|in:Customer,Employee',
        ]);

        NguoiDung::create([
            'tenNguoiDung' => $request->tenNguoiDung,
            'email' => $request->email,
            'matKhau' => Hash::make($request->matKhau),
            'vaiTro' => $request->vaiTro,
        ]);

        return redirect('/login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}