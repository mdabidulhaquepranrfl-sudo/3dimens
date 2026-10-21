<?php

use App\Modules\Store\Controllers\CartController;
use App\Modules\Store\Controllers\CheckoutController;
use App\Modules\Store\Controllers\HomeController;
use App\Modules\Store\Controllers\OrderController;
use App\Modules\Store\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('store')->name('store.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});
