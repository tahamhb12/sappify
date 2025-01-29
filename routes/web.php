<?php

use App\Filament\Affiliate\Pages\Profile;
use App\Filament\Affiliate\Pages\RegisterAffiliate;
use App\Filament\Resources\AffiliateProgramResource\Pages\AffiliateRequest;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\ShopifyAppController;
use App\Http\Controllers\ShopifyAppEventController;
use App\Http\Middleware\AuthCheck;
use App\Models\User;
use App\Notifications\ReferralRequest;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
    })->middleware(AuthCheck::class);
// Route::get('/test', [ShopifyAppController::class, 'test']);
/*     Route::get('/shopify/app', [ShopifyAppController::class, 'App']);
Route::get('/shopify/test', [ShopifyAppController::class, 'test']);
Route::get('/shopify/event', [ShopifyAppEventController::class, 'Events']);
Route::get('/shopify/event/store', [ShopifyAppEventController::class, 'store']); */

Route::fallback(function () {
    return redirect('/admin');
});
/* Route::get('/affiliate/request/{app_id}/{unique_id}', [AffiliateController::class, 'showAffiliateRequest']);
 */

Route::get('/affiliate/request/{app_id}/{unique_id}', \App\Filament\Affiliate\Pages\AffiliateRequest::class);
Route::get('/affiliate/register', RegisterAffiliate::class)->name('affiliate.registerPage');

