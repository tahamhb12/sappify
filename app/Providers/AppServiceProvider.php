<?php

namespace App\Providers;

use App\Models\BillingEvents;
use App\Observers\BillingEventObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        BillingEvents::observe(BillingEventObserver::class);
    }
}
