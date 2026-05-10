<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/test', function () {
        return response()->json(['message' => 'API v1 hoat dong']);
    });

    // ==================== AUTH ====================
    Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/login',    [App\Http\Controllers\Api\AuthController::class, 'login']);

    // ==================== PUBLIC ====================
    Route::get('/products', [App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show']);

    // ==================== CẦN ĐĂNG NHẬP ====================
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::get('/profile', [App\Http\Controllers\Api\AuthController::class, 'profile']);
    });
});