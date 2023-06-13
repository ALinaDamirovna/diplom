<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {

    Route::middleware('can:crud-products')->group(function () {
        Route::resource('products', App\Http\Controllers\ProductController::class);
        Route::post('/products.stop/{id}', [App\Http\Controllers\ProductController::class, 'stop'])->name('products.stop');
        Route::resource('product_cats', App\Http\Controllers\ProductCategoryController::class);

        Route::resource('promocodes', App\Http\Controllers\PromocodeController::class);

        Route::resource('options', App\Http\Controllers\ProductOptionController::class);
        Route::resource('option_cats', App\Http\Controllers\ProductOptionGroupController::class);

        Route::resource('additions', App\Http\Controllers\ProductAdditionController::class);

        Route::get('settings', [App\Http\Controllers\SettingController::class, 'pageDelivery']);
        Route::post('settings', [App\Http\Controllers\SettingController::class, 'saveDelivery']);
    });

    Route::middleware('can:change-orders')->group(function () {
        Route::resource('orders', App\Http\Controllers\OrderController::class);
    });
});
