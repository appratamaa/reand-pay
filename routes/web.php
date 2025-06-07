<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [CheckoutController::class, 'index']);
Route::post('/checkout', [CheckoutController::class, 'checkoutAjax']);
Route::get('/payment/{reference}', [CheckoutController::class, 'paymentStatus']);
