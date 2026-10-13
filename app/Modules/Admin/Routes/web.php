<?php

use App\Modules\Admin\Controllers\AdminCategoryController;
use App\Modules\Admin\Controllers\AdminOrderController;
use App\Modules\Admin\Controllers\AdminProductController;
use App\Modules\Admin\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Categories
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        // Products
        Route::resource('products', AdminProductController::class);

        // Orders & Fulfillment
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/verify', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify');
        Route::post('/orders/{id}/ship', [AdminOrderController::class, 'updateShipment'])->name('orders.ship');
        Route::post('/orders/{id}/cancel', [AdminOrderController::class, 'cancelOrder'])->name('orders.cancel');
    });
