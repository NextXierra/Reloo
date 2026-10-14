<?php

use App\Modules\Shipping\Controllers\ShippingApiController;
use App\Modules\Shipping\Controllers\ShippingDeliveryWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('shipping')->name('api.shipping.')->group(function () {
    Route::get('/destinations', [ShippingApiController::class, 'destinations'])->name('destinations');
    Route::post('/calculate', [ShippingApiController::class, 'calculate'])->name('calculate');
    Route::post('/track', [ShippingApiController::class, 'track'])->name('track');
    Route::match(['post', 'put'], '/webhook', [ShippingDeliveryWebhookController::class, 'handle'])->name('webhook');
});
