<?php

use App\Http\Controllers\Dashboard\SliderController;
use App\Http\Controllers\Dashboard\PortfolioController;
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
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\OrderMediaController;
use App\Http\Controllers\Dashboard\SettingController;


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
            Route::resource('notifications', NotificationController::class);

            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::resource('companies', CompanyController::class)->except(['show']);
            Route::resource('mosques', MosqueController::class)->except(['show']);
            Route::post('/mosques/toggle-availability', [MosqueController::class, 'toggleAvailability'])->name('mosques.toggleAvailability');

            Route::resource('products', ProductController::class)->except(['show']);
            Route::resource( 'settings', SettingController::class)->except(['show']);

            Route::resource('users', UserController::class)->except(['show']);
            Route::resource('clients', ClientController::class)->except(['show']);
            Route::resource('clients.orders', ClientOrderController::class)->except(['show']);

            Route::get('/invoice/{id}/download', [OrderController::class, 'downloadInvoice'])->name('invoice.download');
            Route::resource('orders', OrderController::class)->except(['show']);

            Route::get('order/{orderId}/media', [OrderMediaController::class, 'index'])->name('order.media.index');
            Route::post('order/media/store', [OrderMediaController::class, 'store'])->name('order.media.store');
            Route::delete('order/media/{id}', [OrderMediaController::class, 'destroy'])->name('order.media.delete');
            Route::get('order/confirm/{id}', [OrderController::class, 'confirmOrder'])->name('order.confirmOrder');


            Route::post('portfolio/media/store', [PortfolioController::class, 'store'])->name('portfolio.media.store');
            Route::get('portfolio/media', [PortfolioController::class, 'index'])->name('portfolio.media.index');
            Route::delete('/portfolio/media/{id}', [PortfolioController::class, 'destroy'])->name('portfolio.media.delete');

            Route::post('slider/media/store', [SliderController::class, 'store'])->name('slider.media.store');
            Route::get('slider/media', [SliderController::class, 'index'])->name('slider.media.index');
            Route::delete('/slider/media/{id}', [SliderController::class, 'destroy'])->name('slider.media.delete');



            Route::get('/orders/{order}/products', [OrderController::class, 'products'])->name('orders.products');

        });

    } // <== Closing bracket for Route::group()
);
