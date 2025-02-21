<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MosqueController;
use App\Http\Controllers\API\ProductCompanyController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PortfolioController;
use App\Http\Controllers\API\SliderController;
use App\Services\OtpService;

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
    Route::post('profile', [AuthController::class, 'updateProfile'])->name('auth.update-profile');
    Route::get('delete_account', [AuthController::class,'deleteAccount']);

    // Mosque Routes
    Route::get('mosques', [MosqueController::class, 'list'])->name('mosques.list');
    Route::get('categories', [MosqueController::class, 'listCategories'])->name('categories.list');

    Route::get('mosques/{id}', [MosqueController::class, 'details'])->name('mosques.details');

    // Company and Product Routes
    Route::get('companies', [ProductCompanyController::class, 'listCompanies'])->name('companies.list');
    Route::get('products', [ProductCompanyController::class, 'listProducts'])->name('products.list');
    Route::get('products/{id}', [ProductCompanyController::class, 'productDetails'])->name('products.details');

    //order Routes

    Route::post('/order/create', [OrderController::class, 'createOrder']);
    Route::get('/orders/{id}', [OrderController::class, 'viewOrder']);
    Route::get('/order/pay/{id}', [OrderController::class, 'pay']);

    Route::get('/my-orders', [OrderController::class, 'getMyOrders']);
    Route::get('/orders/last-activities/{limit?}', [OrderController::class, 'lastActivities']);
    Route::get('order/{orderId}/media', [OrderController::class, 'getMedia'])->name('order.media.index');
    Route::post('/orders/remake/{orderId}', [OrderController::class, 'remakeOrder']);


    Route::get('/portfolios', [PortfolioController::class, 'index']); // List all portfolios
    Route::get('/settings', [PortfolioController::class, 'getSettings']); // List all portfolios


    Route::get('/portfolios/{id}', [PortfolioController::class, 'show']); // Get a specific portfolio

    Route::get('/sliders', action: [SliderController::class, 'index']); // List all portfolios

    Route::post('update-user-token',[AuthController::class,'updateUserToken']);
    Route::get('unread-messages-count', [AuthController::class,'unReadMessagesCount']);
    Route::get('read-notifications', [AuthController::class,'readNotification']);
    Route::get('get-notifications', [AuthController::class,'getNotifications']);
    Route::get('/send-otp', function () {
        $authController = new AuthController();

        $response = $authController->sendOtp('+96550768928', '123456'); // Replace with a real phone number
        return response()->json($response);
    });

});
