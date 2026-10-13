<?php

use App\Modules\Catalog\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/catalog', [ProductController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [ProductController::class, 'show'])->name('catalog.show');
