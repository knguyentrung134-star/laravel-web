<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TraHangController;

use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KhachHangController;
use App\Http\Controllers\Admin\SanPhamController;
use App\Http\Controllers\Admin\DonHangController;
use App\Http\Controllers\Admin\HangTonKhoController;
use App\Http\Controllers\Admin\BaoCaoDoanhThuController;
use App\Http\Controllers\Admin\ChuongTrinhGiamGiaController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/sanpham/{sanpham}', [\App\Http\Controllers\SanPhamController::class, 'show'])
    ->name('sanpham.show');

Route::get('/search', [HomeController::class, 'search'])->name('search');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Báo cáo
    Route::get('/bao-cao-doanh-thu', [BaoCaoDoanhThuController::class, 'index'])
        ->name('bao-cao-doanh-thu');

    // ===== QUẢN LÝ =====
    Route::resource('khachhang', KhachHangController::class);

    Route::resource('sanpham', SanPhamController::class);

    Route::resource('donhang', DonHangController::class);

    Route::resource('giamgia', ChuongTrinhGiamGiaController::class)
    ->parameters([
        'giamgia' => 'giamgia'
    ]);

    Route::resource('khuyenmai', KhuyenMaiController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // ===== KHO =====
    Route::resource('kho', HangTonKhoController::class)
        ->only(['index', 'create', 'store']);

    Route::get('kho/lichsu', [HangTonKhoController::class, 'lichsu'])
        ->name('kho.lichsu');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Áp mã giảm giá
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])
        ->name('checkout.apply-coupon');

    // Trả hàng
    Route::get('/tra-hang', [TraHangController::class, 'index'])->name('trahang.index');
    Route::post('/tra-hang', [TraHangController::class, 'store'])->name('trahang.store');

    // Lịch sử
    Route::get('/lichsu-muahang', [OrderHistoryController::class, 'index'])
        ->name('order.history');

    // Đánh giá
    Route::post('/sanpham/{sanpham}/danhgia', [DanhGiaController::class, 'store'])
        ->name('danhgia.store');

    // Giỏ hàng
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
});