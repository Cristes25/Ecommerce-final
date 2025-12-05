<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ShoppingCartController;
use App\Http\Controllers\Api\OrdersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

// --------------------
// Public Routes
// --------------------

// Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Product browsing
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

// Category browsing
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

// --------------------
// Protected Routes (auth:sanctum)
// --------------------
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('logout', [AuthController::class, 'logout']);

    // Admin Product Management
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    // Admin Category Management
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    // Shopping Cart
    Route::get('cart', [ShoppingCartController::class, 'index']);
    Route::post('cart', [ShoppingCartController::class, 'addItem']);
    Route::put('cart/{itemId}', [ShoppingCartController::class, 'updateItem']);
    Route::delete('cart/{itemId}', [ShoppingCartController::class, 'removeItem']);
    Route::delete('cart', [ShoppingCartController::class, 'clear']);

    // Orders
    Route::get('orders', [OrdersController::class, 'index']);
    Route::get('orders/{id}', [OrdersController::class, 'show']);
    Route::post('checkout', [OrdersController::class, 'checkout']);
    Route::patch('orders/{id}/status', [OrdersController::class, 'updateStatus']); // admin only
});
