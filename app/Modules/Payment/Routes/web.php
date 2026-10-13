<?php

use App\Modules\Payment\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/payment/{order_number}', [PaymentController::class, 'pay'])
        ->where('order_number', '.*')
        ->name('payment.pay');

    Route::post('/payment/manual/{order_number}/upload', [PaymentController::class, 'uploadProof'])
        ->where('order_number', '.*')
        ->name('payment.manual.upload');

    Route::post('/payment/mock-success/{order_number}', [PaymentController::class, 'mockSuccess'])
        ->where('order_number', '.*')
        ->name('payment.mock.success');
});
