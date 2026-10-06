<?php

use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/shop', [CatalogController::class, 'index'])->name('shop.index');
Route::get('/products/{product}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/about', [CatalogController::class, 'about'])->name('about');
