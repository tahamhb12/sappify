<?php

namespace App\Providers;

use App\Models\BillingEvents;
use App\Models\Payout;
use App\Models\Referral;
use App\Observers\BillingEventObserver;
use App\Observers\PayoutObserver;
use App\Observers\ReferralObserver;
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
        Referral::observe(ReferralObserver::class);
        Payout::observe(PayoutObserver::class);
    }
}
