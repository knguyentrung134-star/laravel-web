<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string|unique:nguoidung,ten_dang_nhap',
            'email'         => 'required|email|unique:nguoidung,email',
            'mat_khau'      => 'required|string|min:6',
            'ho_ten'        => 'required|string',
            'so_dien_thoai' => 'nullable|string',
        ]);

        $user = NguoiDung::create([
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'email'         => $request->email,
            'mat_khau'      => Hash::make($request->mat_khau),
            'ho_ten'        => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'vai_tro'       => 'khach_hang', // mặc định
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng ký thành công',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    // Đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string',
            'mat_khau'      => 'required|string',
        ]);

        $user = NguoiDung::where('ten_dang_nhap', $request->ten_dang_nhap)->first();

        if (!$user || !Hash::check($request->mat_khau, $user->mat_khau)) {
            throw ValidationException::withMessages([
                'ten_dang_nhap' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user'    => $user,
            'token'   => $token
        ]);
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
    }

    // Lấy thông tin user
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
}