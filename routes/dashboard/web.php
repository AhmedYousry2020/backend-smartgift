<?php

use App\Http\Controllers\Dashboard\MosqueController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\LoginController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\Dashboard\OrderController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {  // <== Make sure this is properly opened

        // 🔹 Admin Login Routes
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::middleware('guest:admin')->group(function () {
                Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
                Route::post('login', [LoginController::class, 'login']);

            });
        });

        // 🔹 Dashboard Routes (Admin Only)
        Route::prefix('dashboard')->middleware('admin')->name('dashboard.')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('index');
            Route::post('logout', [LoginController::class, 'logout'])->name('logout');

            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::resource('companies', CompanyController::class)->except(['show']);
            Route::resource('mosques', MosqueController::class)->except(['show']);
            Route::resource('products', ProductController::class)->except(['show']);
            Route::resource('users', UserController::class)->except(['show']);
            Route::resource('clients', ClientController::class)->except(['show']);
            Route::resource('clients.orders', ClientOrderController::class)->except(['show']);
            Route::resource('orders', OrderController::class)->except(['show']);

            Route::get('/orders/{order}/products', [OrderController::class, 'products'])->name('orders.products');
        });

    } // <== Closing bracket for Route::group()
);
