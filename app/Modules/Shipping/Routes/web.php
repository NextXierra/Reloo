<?php

use App\Modules\Shipping\Controllers\AdminShippingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::post('/orders/{id}/shipping/store-order', [AdminShippingController::class, 'storeOrder'])
            ->name('shipping.store');
        Route::post('/orders/{id}/shipping/pickup', [AdminShippingController::class, 'requestPickup'])
            ->name('shipping.pickup');
        Route::get('/orders/{id}/shipping/label', [AdminShippingController::class, 'printLabel'])
            ->name('shipping.label');
        Route::post('/orders/{id}/shipping/cancel', [AdminShippingController::class, 'cancel'])
            ->name('shipping.cancel');
        Route::get('/orders/{id}/shipping/track', [AdminShippingController::class, 'track'])
            ->name('shipping.track');
    });
