<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MosqueController;
use App\Http\Controllers\API\ProductCompanyController;
use App\Http\Controllers\API\AuthController;

Route::group([
    'prefix' => 'v1',
    'middleware' => ['api', 'set_locale']
], function () {

    // Authentication Routes
    Route::post('register', [AuthController::class, 'signUp'])->name('auth.sign-up');
    Route::post('verify', [AuthController::class, 'verify'])->name('auth.verify');
    Route::post('resend-code', [AuthController::class, 'resendOtpCode'])->name('auth.resend-otp');
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('auth.refresh-token');
    Route::post('login', [AuthController::class, 'signIn'])->name('auth.sign-in');
    Route::post('logout', [AuthController::class, 'signOut'])->name('auth.sign-out');
    Route::get('details/profile', [AuthController::class, 'profile'])->name('auth.profile');

    // Mosque Routes
    Route::get('mosques', [MosqueController::class, 'list'])->name('mosques.list');
    Route::get('mosques/{id}', [MosqueController::class, 'details'])->name('mosques.details');

    // Company and Product Routes
    Route::get('companies', [ProductCompanyController::class, 'listCompanies'])->name('companies.list');
    Route::get('products', [ProductCompanyController::class, 'listProducts'])->name('products.list');
    Route::get('products/{id}', [ProductCompanyController::class, 'productDetails'])->name('products.details');

});
