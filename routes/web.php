<?php

use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ShopifyAppController;
use App\Http\Controllers\ShopifyAppEventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/shopify/app', [ShopifyAppController::class, 'App']);
Route::get('/shopify/store', [ShopifyAppController::class, 'store']);

Route::get('/shopify/event', [ShopifyAppEventController::class, 'Events']);
Route::get('/shopify/event/store', [ShopifyAppEventController::class, 'store']);
