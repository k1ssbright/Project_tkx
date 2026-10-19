<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

// Public Front-end Routes
Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/shop', [CatalogController::class, 'index'])->name('shop.index');
Route::get('/products/{product}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/about', [CatalogController::class, 'about'])->name('about');
Route::get('/contact', [CatalogController::class, 'contact'])->name('contact');
Route::get('/articles', [CatalogController::class, 'articles'])->name('articles.index');
Route::get('/articles/{article}', [CatalogController::class, 'article'])->name('articles.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Backend Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::resource('articles', AdminArticleController::class)->except(['show']);
    });

    // Legacy manage routes compatibility
    Route::get('/manage/products', [AdminProductController::class, 'index'])->name('products.manage.index');
    Route::get('/manage/products/create', [AdminProductController::class, 'create'])->name('products.manage.create');
    Route::post('/manage/products', [AdminProductController::class, 'store'])->name('products.manage.store');
    Route::get('/manage/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.manage.edit');
    Route::put('/manage/products/{product}', [AdminProductController::class, 'update'])->name('products.manage.update');
    Route::delete('/manage/products/{product}', [AdminProductController::class, 'destroy'])->name('products.manage.destroy');
});
