<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    Route::middleware(['auth'])->group(function () {
        Route::get('/home', [ProductController::class,'productList'])->name('home');
        Route::get('/details/{id?}', [ProductController::class,'productDetails'])->name('productDetails');
        Route::post('/payment', [PaymentController::class, 'checkout'])->name('payment.process');
        Route::post('/charge', [PaymentController::class, 'charge'])->name('payment.charge');
        Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/payment/failure/{id?}', [PaymentController::class, 'failure'])->name('payment.failure');
    });
    Auth::routes();

