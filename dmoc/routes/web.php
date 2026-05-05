<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\AdminController;

Route::redirect('/', '/dmoc/home');
Route::redirect('/login', '/dmoc/auth')->name('login');

Route::prefix('dmoc')->group(function () {
    Route::get('/home',         [ClientController::class, 'home'])->name('client.home');
    Route::get('/catalog',      [ClientController::class, 'catalog'])->name('client.catalog');
    Route::get('/product',      [ClientController::class, 'product'])->name('client.product');
    Route::get('/tracking',     [ClientController::class, 'tracking'])->name('client.tracking');
    Route::get('/confirmation', [ClientController::class, 'confirmation'])->name('client.confirmation');
    Route::get('/auth',         [WebAuthController::class, 'showAuthForm'])->name('client.auth');
});

Route::prefix('dmoc')->middleware(['auth', 'role:client,vendeur,admin,superadmin'])->group(function () {
    Route::get('/cart',         [ClientController::class, 'cart'])->name('client.cart');
    Route::get('/checkout',     [ClientController::class, 'checkout'])->name('client.checkout');
    Route::get('/dashboard',    [ClientController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/orders',       [ClientController::class, 'orders'])->name('client.orders');
    Route::get('/orders/{orderId}', [ClientController::class, 'orderShow'])->name('client.orders.show');
    Route::post('/checkout/step-1', [ClientController::class, 'checkoutStepOne'])->name('client.checkout.step1');
    Route::post('/checkout/{orderId}/step-2', [ClientController::class, 'checkoutStepTwo'])->name('client.checkout.step2');
    Route::post('/checkout/{orderId}/confirm', [ClientController::class, 'checkoutConfirm'])->name('client.checkout.confirm');
    Route::get('/wishlist',     [ClientController::class, 'wishlist'])->name('client.wishlist');
    Route::get('/profile',      [ClientController::class, 'profile'])->name('client.profile');
});

Route::post('/auth/register', [WebAuthController::class, 'register'])->name('auth.register');
Route::post('/auth/login', [WebAuthController::class, 'login'])->name('auth.login');
Route::post('/auth/logout', [WebAuthController::class, 'logout'])->name('auth.logout')->middleware('auth');

Route::prefix('dmoc')->middleware(['auth', 'role:livreur,admin,superadmin'])->group(function () {
    Route::get('/courier-list',   [CourierController::class, 'list'])->name('courier.list');
    Route::get('/courier-detail/{deliveryId}', [CourierController::class, 'detail'])->name('courier.detail');
    Route::get('/courier-deliveries/{deliveryId}/receipt', [CourierController::class, 'receipt'])->name('courier.deliveries.receipt');
    Route::post('/courier-deliveries/{deliveryId}/start', [CourierController::class, 'start'])->name('courier.deliveries.start');
    Route::post('/courier-deliveries/{deliveryId}/complete', [CourierController::class, 'complete'])->name('courier.deliveries.complete');
    Route::post('/courier-deliveries/{deliveryId}/fail', [CourierController::class, 'fail'])->name('courier.deliveries.fail');
    Route::post('/courier-deliveries/{deliveryId}/confirm-cod', [CourierController::class, 'confirmCodPayment'])->name('courier.deliveries.confirm-cod');
});

Route::prefix('dmoc')->middleware(['auth', 'role:admin,superadmin'])->group(function () {
    Route::get('/admin-kpi',      [AdminController::class, 'kpi'])->name('admin.kpi');
    Route::get('/admin-products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/admin-products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::put('/admin-products/{productId}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/admin-products/{productId}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    Route::get('/admin-categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/admin-categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/admin-categories/{categoryId}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin-categories/{categoryId}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
    Route::get('/admin-orders',   [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin-orders/{orderId}', [AdminController::class, 'orderShow'])->name('admin.orders.show');
    Route::post('/admin-orders/{orderId}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    Route::post('/admin-orders/{orderId}/cancel', [AdminController::class, 'cancelOrder'])->name('admin.orders.cancel');
    Route::post('/admin-orders/{orderId}/assign-courier', [AdminController::class, 'assignCourier'])->name('admin.orders.assign-courier');
    Route::get('/admin-couriers', [AdminController::class, 'couriers'])->name('admin.couriers');
    Route::post('/admin-couriers', [AdminController::class, 'storeCourier'])->name('admin.couriers.store');
    Route::put('/admin-couriers/{courierId}', [AdminController::class, 'updateCourier'])->name('admin.couriers.update');
    Route::post('/admin-couriers/{courierId}/toggle', [AdminController::class, 'toggleCourierStatus'])->name('admin.couriers.toggle');
    Route::get('/admin-couriers/{courierId}', [AdminController::class, 'courierShow'])->name('admin.couriers.show');
    Route::get('/admin-zones',    [AdminController::class, 'zones'])->name('admin.zones');
    Route::post('/admin-zones',    [AdminController::class, 'storeZone'])->name('admin.zones.store');
    Route::put('/admin-zones/{zoneId}',    [AdminController::class, 'updateZone'])->name('admin.zones.update');
    Route::delete('/admin-zones/{zoneId}',    [AdminController::class, 'destroyZone'])->name('admin.zones.destroy');
    Route::get('/admin-settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin-settings', [AdminController::class, 'saveSettings'])->name('admin.settings.save');
});
