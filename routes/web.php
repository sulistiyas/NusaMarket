<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\CatalogController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Api\ReportController as ApiReportController;

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

    // Marketplace Catalog (Phase 7)
    Route::get('/marketplace', [CatalogController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/{product}', [CatalogController::class, 'show'])->name('marketplace.show');

    // Cart (Phase 7)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout (Phase 7)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders (Phase 8)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Reports (Phase 9)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');

    // Reports API AJAX endpoints (session auth, diakses via JS fetch dari halaman report)
    Route::prefix('api/v1/reports')->group(function () {
        Route::get('/chart/revenue',      [ApiReportController::class, 'chartRevenue']);
        Route::get('/chart/status',       [ApiReportController::class, 'chartStatus']);
        Route::get('/chart/top-products', [ApiReportController::class, 'chartTopProducts']);
        Route::get('/chart/user-growth',  [ApiReportController::class, 'chartUserGrowth']);
        Route::get('/table/summary',      [ApiReportController::class, 'tableSummary']);
    });
});
