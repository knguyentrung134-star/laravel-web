<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ================= ĐĂNG KÝ =================
    public function register(Request $request)
    {
        $request->validate([
            'tenNguoiDung' => 'required|string|unique:nguoidung,tenNguoiDung',
            'email'        => 'required|email|unique:nguoidung,email',
            'matKhau'      => 'required|string|min:6',
        ]);

        $user = NguoiDung::create([
            'tenNguoiDung' => $request->tenNguoiDung,
            'email'        => $request->email,
            'matKhau'      => Hash::make($request->matKhau),
            'vaiTro'       => 'Customer',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng ký thành công',
            'user'    => $user,
            'token'   => $token
        ], 201, [], JSON_UNESCAPED_UNICODE);
    }

    // ================= ĐĂNG NHẬP =================
    public function login(Request $request)
    {
        $request->validate([
            'tenNguoiDung' => 'required|string',
            'matKhau'      => 'required|string',
        ], [
            'tenNguoiDung.required' => 'Vui lòng nhập tên đăng nhập',
            'matKhau.required'      => 'Vui lòng nhập mật khẩu',
        ]);

        $user = NguoiDung::where(
            'tenNguoiDung',
            $request->tenNguoiDung
        )->first();

        if (!$user || !Hash::check($request->matKhau, $user->matKhau)) {

            return response()->json([
                'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác'
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user'    => $user,
            'token'   => $token,
            'token_type' => 'Bearer'
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    // ================= ĐĂNG XUẤT =================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công'
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    // ================= PROFILE =================
    public function profile(Request $request)
    {
        return response()->json(
            $request->user(),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}