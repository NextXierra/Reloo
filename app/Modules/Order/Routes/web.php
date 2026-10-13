<?php

use App\Modules\Order\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');

    Route::get('/orders/{order_number}', [OrderController::class, 'show'])
        ->where('order_number', '.*')
        ->name('order.show');

    Route::get('/orders/{order_number}/invoice', [OrderController::class, 'invoice'])
        ->where('order_number', '.*')
        ->name('order.invoice');
});
