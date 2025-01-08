<?php

namespace App\Observers;

use App\Models\BillingEvent;
use App\Models\BillingEvents;
use App\Models\Earning;
use App\Models\Referral;

class BillingEventObserver
{
    /**
     * Handle the BillingEvent "created" event.
     */
    public function created(BillingEvents $billingEvent): void
    {
/*                 $referral = Referral::where('customer_shop', $billingEvent->shop->myshopifyDomain)
                ->where('status', 'approved')
                ->first();

            if ($referral) {
                $commission = $billingEvent->amount * ($referral->affiliateProgram->commission_rate/100);

                $user_earnings = Earning::where('user_id',$referral->user_id)->latest()->first()->earnings ?? 0;
                Earning::updateOrCreate(
                    ['user_id' => $referral->user_id],
                    ['earnings' => $user_earnings + $commission]
                );
            } */
            $referrals = Referral::where('customer_shop', $billingEvent->shop->myshopifyDomain)
            ->where('status', 'approved')
            ->get();

        if ($referrals->isNotEmpty()) {
            foreach ($referrals as $referral) {
                if($referral->affiliateProgram->app->id == $billingEvent->app->id){
                    $commission = $billingEvent->amount * ($referral->affiliateProgram->commission_rate / 100);

                    Earning::create([
                        'user_id' => $referral->user_id,
                        'earnings' => $commission,
                        'type' => $billingEvent->type,
                        'billing_event_id' => $billingEvent->id
                    ]);
                }
            }
        }


    }

    /**
     * Handle the BillingEvents "updated" event.
     */
    public function updated(BillingEvents $billingEvent): void
    {
        //
    }

    /**
     * Handle the BillingEvents "deleted" event.
     */
    public function deleted(BillingEvents $billingEvent): void
    {
        //
    }

    /**
     * Handle the BillingEvents "restored" event.
     */
    public function restored(BillingEvents $billingEvent): void
    {
        //
    }

    /**
     * Handle the BillingEvents "force deleted" event.
     */
    public function forceDeleted(BillingEvents $billingEvent): void
    {
        //
    }
}
