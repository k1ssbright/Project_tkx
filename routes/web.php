<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductViewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/shop', [CatalogController::class, 'index'])->name('shop.index');
Route::get('/products/{product}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/about', [CatalogController::class, 'about'])->name('about');
Route::get('/news', [CatalogController::class, 'news'])->name('news.index');
Route::get('/news/{article}', [CatalogController::class, 'article'])->name('news.show');
Route::permanentRedirect('/articles/{article}', '/news/{article}');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/views', ProductViewController::class)->name('views');
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('news', NewsController::class)->except('show')->parameters(['news' => 'article']);
});
