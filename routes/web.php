<?php

use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\ShopifyAppController;
use App\Http\Controllers\ShopifyAppEventController;
use App\Http\Middleware\AffiliateCheck;
use App\Http\Middleware\RestrictNonAdminAccess;
use Illuminate\Support\Facades\Route;


    Route::get('/', function () {
        return view('welcome');
    });
/*     Route::get('/shopify/app', [ShopifyAppController::class, 'App']);
    Route::get('/shopify/test', [ShopifyAppController::class, 'test']);

    Route::get('/shopify/event', [ShopifyAppEventController::class, 'Events']);
    Route::get('/shopify/event/store', [ShopifyAppEventController::class, 'store']); */

Route::fallback(function () {
    return redirect('/admin');
});
Route::get('/affiliate/request/{app_id}/{unique_id}', [AffiliateController::class, 'showAffiliateRequest']);
Route::get('/affiliate/register/{app_id}/{unique_id}', [AffiliateController::class, 'affiliateRegisterPage'])->name('affiliate.registerPage');;
Route::post('/affiliate/register', [AffiliateController::class, 'register'])->name('affiliate.register');;

