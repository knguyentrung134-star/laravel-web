<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SanPhamController;
use App\Http\Controllers\Admin\DonHangController;
use App\Http\Controllers\Admin\HangTonKhoController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// ====================== AUTH ======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ====================== PUBLIC ======================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Chi tiết sản phẩm
Route::get('/sanpham/{sanpham}', [\App\Http\Controllers\SanPhamController::class, 'show'])
    ->name('sanpham.show');

// Tìm kiếm
Route::get('/search', [HomeController::class, 'search'])->name('search');

// ====================== ADMIN ======================
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // === BÁO CÁO DOANH THU ===
    Route::get('/bao-cao-doanh-thu', [App\Http\Controllers\Admin\BaoCaoDoanhThuController::class, 'index'])
        ->name('bao-cao-doanh-thu');

    Route::resource('sanpham', SanPhamController::class);
    Route::resource('donhang', DonHangController::class);

    Route::resource('kho', HangTonKhoController::class)
        ->only(['index', 'create', 'store']);

    Route::get('kho/lichsu', [HangTonKhoController::class, 'lichsu'])
        ->name('kho.lichsu');

    Route::resource('khuyenmai', KhuyenMaiController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});

// ====================== CUSTOMER ======================
Route::middleware(['auth'])->group(function () {

    Route::get('/lichsu-muahang', [OrderHistoryController::class, 'index'])
        ->name('order.history');    

    Route::post('/sanpham/{sanpham}/danhgia', [DanhGiaController::class, 'store'])
        ->name('danhgia.store');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});