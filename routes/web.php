<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/shop', [CatalogController::class, 'index'])->name('shop.index');
Route::get('/products/{product}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/about', [CatalogController::class, 'about'])->name('about');
Route::get('/articles/{article}', [CatalogController::class, 'article'])->name('articles.show');

Route::get('/manage/products', [ProductController::class, 'index'])->name('products.manage.index');
Route::get('/manage/products/create', [ProductController::class, 'create'])->name('products.manage.create');
Route::post('/manage/products', [ProductController::class, 'store'])->name('products.manage.store');
Route::get('/manage/products/{product}/edit', [ProductController::class, 'edit'])->name('products.manage.edit');
Route::put('/manage/products/{product}', [ProductController::class, 'update'])->name('products.manage.update');
Route::delete('/manage/products/{product}', [ProductController::class, 'destroy'])->name('products.manage.destroy');
