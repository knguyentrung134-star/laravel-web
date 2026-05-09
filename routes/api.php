<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;

Route::prefix('v1')->group(function () {

    // ==================== API CÔNG KHAI ====================
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{sanpham}', [ProductController::class, 'show']);

    // ==================== API CẦN ĐĂNG NHẬP ====================
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/user', function (Request $request) {
            return $request->user();   // Trả về NguoiDung
        });

        // Giỏ hàng
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index']);
            Route::post('/add', [CartController::class, 'add']);
            Route::post('/update', [CartController::class, 'update']);
            Route::delete('/remove/{id}', [CartController::class, 'remove']);
            Route::post('/clear', [CartController::class, 'clear']);
        });

        // Đơn hàng
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::post('/', [OrderController::class, 'store']);
            Route::get('/{donhang}', [OrderController::class, 'show']);
        });
    });
});