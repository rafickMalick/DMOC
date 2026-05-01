<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/item', [CartController::class, 'updateItem']);
    Route::post('/cart/remove', [CartController::class, 'remove']);
    Route::post('/checkout/step-1', [CheckoutController::class, 'stepOne']);
    Route::post('/checkout/{orderId}/step-2', [CheckoutController::class, 'stepTwo']);
    Route::get('/checkout/{orderId}/summary', [CheckoutController::class, 'summary']);
    Route::post('/checkout/{orderId}/confirm', [CheckoutController::class, 'confirm']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
