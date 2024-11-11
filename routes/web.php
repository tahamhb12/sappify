<?php

use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ShopifyAppController;
use App\Http\Controllers\ShopifyAppEventController;
use App\Http\Middleware\RestrictNonAdminAccess;
use Illuminate\Support\Facades\Route;


Route::middleware(RestrictNonAdminAccess::class)->group(function(){

    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/shopify/app', [ShopifyAppController::class, 'App']);
    Route::get('/shopify/test', [ShopifyAppController::class, 'test']);

    Route::get('/shopify/event', [ShopifyAppEventController::class, 'Events']);
    Route::get('/shopify/event/store', [ShopifyAppEventController::class, 'store']);
});
Route::fallback(function () {
    return redirect('/admin');
});
