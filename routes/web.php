<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {  // <== Make sure this is properly opened
        Route::get('/privacy', function () {
            $settings = Setting::with(['translations' => function ($query) {
                $query->where('locale', app()->getLocale());
            }])->get();
            return view('privacy',compact('settings'));
        });

        Route::get('/', function () {
            return view('welcome');
        });
        Route::get('/contact-us', function () {
            return view('contact_us');
        });
        Route::post('/contact-us', function (Request $request) {
            return redirect()->back()->with('success', __('site.Your message has been sent successfully! We will get back to you soon.'));
        })->name('contact.submit');

    });
