<?php

use App\Modules\Catalog\Controllers\HomeController;
use App\Modules\Catalog\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [ProductController::class, 'show'])->name('catalog.show');
