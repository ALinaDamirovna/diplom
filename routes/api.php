<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/orders', [OrderController::class, 'getUserOrders']);
});

Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
Route::post('/register',[ApiAuthController::class, 'register'])->name('register.api');
Route::post('/recovery',[ApiAuthController::class, 'recovery'])->name('recovery.api');

Route::middleware('throttle:none')->group(function(){
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'getList']);
        Route::get('/{id}', [ProductController::class, 'getById']);
    });
    Route::prefix('categories')->group(function () {
        Route::get('/', [ProductCategoryController::class, 'getList']);
    });

    Route::prefix('order')->group(function () {
        Route::post('/', [OrderController::class, 'createOrder']);
        Route::post('/calc', [OrderController::class, 'calcOrder']);
        Route::get('/{id}/{hash}', [OrderController::class, 'getOrderInfo']);
    });
});

Route::post('/test', [ProductController::class, 'saveFile']);
