<?php

use App\Modules\Admin\Controllers\AuthController;
use App\Modules\Admin\Controllers\CustomerController;
use App\Modules\Admin\Controllers\DashboardController;
use App\Modules\Admin\Controllers\OrderController;
use App\Modules\Admin\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Authenticated Admin Routes (restricted to admin_users guard)
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Products CRUD
        Route::resource('products', ProductController::class);

        // Customers CRUD + quick store for modal in orders
        Route::post('/customers/quick-store', [CustomerController::class, 'quickStore'])->name('customers.quick-store');
        Route::resource('customers', CustomerController::class);

        // Orders CRUD
        Route::resource('orders', OrderController::class);
    });
});
