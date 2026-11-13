<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// ---------- Halaman publik (pengunjung & user; admin dialihkan ke panel admin) ----------
Route::middleware('not.admin')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/productdetail/{product}', [HomeController::class, 'show'])->name('products.show');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{productId}', [CartController::class, 'update'])->whereNumber('productId')->name('cart.update');
    Route::delete('/cart/{productId}', [CartController::class, 'destroy'])->whereNumber('productId')->name('cart.destroy');

    // Checkout bisa diakses pengunjung: isiannya disimpan lalu diminta login.
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');

    Route::middleware('auth')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });
});

// ---------- Hanya untuk yang belum login ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Panel admin ----------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/products', [Admin\ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [Admin\ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [Admin\ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');

    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}/role', [Admin\UserController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
});
