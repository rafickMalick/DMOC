<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\AdminController;

Route::redirect('/', '/dmoc/home');

Route::prefix('dmoc')->group(function () {
    Route::get('/home',         [ClientController::class, 'home'])->name('client.home');
    Route::get('/catalog',      [ClientController::class, 'catalog'])->name('client.catalog');
    Route::get('/product',      [ClientController::class, 'product'])->name('client.product');
    Route::get('/cart',         [ClientController::class, 'cart'])->name('client.cart');
    Route::get('/checkout',     [ClientController::class, 'checkout'])->name('client.checkout');
    Route::get('/tracking',     [ClientController::class, 'tracking'])->name('client.tracking');
    Route::get('/confirmation', [ClientController::class, 'confirmation'])->name('client.confirmation');
    Route::get('/auth',         [ClientController::class, 'auth'])->name('client.auth');
    Route::get('/dashboard',    [ClientController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/orders',       [ClientController::class, 'orders'])->name('client.orders');
    Route::get('/wishlist',     [ClientController::class, 'wishlist'])->name('client.wishlist');
    Route::get('/profile',      [ClientController::class, 'profile'])->name('client.profile');
});

Route::prefix('dmoc')->group(function () {
    Route::get('/courier-list',   [CourierController::class, 'list'])->name('courier.list');
    Route::get('/courier-detail', [CourierController::class, 'detail'])->name('courier.detail');
});

Route::prefix('dmoc')->group(function () {
    Route::get('/admin-kpi',      [AdminController::class, 'kpi'])->name('admin.kpi');
    Route::get('/admin-products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin-orders',   [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin-couriers', [AdminController::class, 'couriers'])->name('admin.couriers');
    Route::get('/admin-zones',    [AdminController::class, 'zones'])->name('admin.zones');
    Route::get('/admin-settings', [AdminController::class, 'settings'])->name('admin.settings');
});
