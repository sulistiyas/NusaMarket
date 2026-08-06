<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\ProductController;

// Public / Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Categories
    Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);

    // Products
    Route::resource('products', ProductController::class);

    // Marketplace Catalog (Placeholder for Phase 7)
    Route::get('/marketplace', function() {
        return view('pages.products.index', [
            'breadcrumbs' => ['Katalog Marketplace' => '#'],
            'categories' => \App\Models\Category::all()
        ]);
    })->name('marketplace.index');

    // Cart (Placeholder for Phase 7)
    Route::get('/cart', function() {
        return redirect()->route('dashboard')->with('info', 'Modul keranjang akan aktif pada Fase 7.');
    })->name('cart.index');

    // Orders (Placeholder for Phase 8)
    Route::get('/orders', function() {
        return redirect()->route('dashboard')->with('info', 'Modul pesanan akan aktif pada Fase 8.');
    })->name('orders.index');
});
