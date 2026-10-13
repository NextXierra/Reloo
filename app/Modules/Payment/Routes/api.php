<?php

use App\Modules\Payment\Controllers\PaymentController;
use App\Modules\Payment\Controllers\QrislyWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/qris/check/{order_number}', [PaymentController::class, 'checkStatus'])
    ->where('order_number', '.*')
    ->name('api.qris.check');

Route::post('/payment/qrisly/webhook', [QrislyWebhookController::class, 'handle'])
    ->name('payment.qrisly.webhook');
