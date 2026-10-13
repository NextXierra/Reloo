<?php

use App\Modules\Cms\Controllers\AdminCmsController;
use App\Modules\Cms\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Public CMS Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/p/{slug}', [PageController::class, 'show'])->name('cms.page.show');

// Admin CMS Routes
Route::prefix('admin/cms')
    ->name('admin.cms.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [AdminCmsController::class, 'index'])->name('index');
        Route::post('/', [AdminCmsController::class, 'store'])->name('store');
        Route::get('/{id}/builder', [AdminCmsController::class, 'builder'])->name('builder');
        Route::post('/{id}/save', [AdminCmsController::class, 'save'])->name('save');
        Route::post('/{id}/homepage', [AdminCmsController::class, 'setHomepage'])->name('homepage');
        Route::delete('/{id}', [AdminCmsController::class, 'destroy'])->name('destroy');
    });
