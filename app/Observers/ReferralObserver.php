<?php

namespace App\Observers;

use App\Filament\Affiliate\Resources\ReferralResource;
use App\Filament\Resources\AffiliateProgramResource;
use App\Models\AffiliateProgram;
use App\Models\Referral;
use App\Models\User;
use App\Notifications\ReferralAccepted;
use App\Notifications\ReferralRejected;
use App\Notifications\ReferralRequest;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Notification as LaravelNotification;


class ReferralObserver
{
    /**
     * Handle the Referral "created" event.
     */
    public function created(Referral $referral): void
    {
        $recepient = $referral->affiliateProgram->partner->user;
        $partner = $referral->affiliateProgram->partner;
        $program_id = $referral->affiliateProgram->id;

        Notification::make()
            ->title('New Referral Request')
            ->body("A new referral has been submitted and needs approval in $partner->name.")
            ->info()
            ->actions([
                Action::make('viewReferral')
                    ->label('View Referrals')
                    ->url("/admin/$partner->id/affiliate-programs/{$program_id}")
                    ->openUrlInNewTab(),
            ])
            ->sendToDatabase($recepient);
            LaravelNotification::send($recepient, new ReferralRequest($partner->id,$program_id));


    }

    /**
     * Handle the Referral "updated" event.
     */
    public function updated(Referral $referral): void
    {
        $recepient = $referral->user;
        if($referral->status == 'approved'){
            Notification::make()
            ->title('Referral Request Approved')
            ->body("A referral has been approved.")
            ->success()
            ->actions([
                Action::make('viewReferral')
                    ->label('View Referrals')
                    ->url("/affiliate/referrals")
                    ->openUrlInNewTab(),
            ])
            ->sendToDatabase($recepient);
            LaravelNotification::send($recepient, new ReferralAccepted());
        }else if($referral->status == 'rejected'){
            Notification::make()
            ->title('Referral Request Declined')
            ->body("A referral has been Declined.")
            ->danger()
            ->actions([
                Action::make('viewReferral')
                    ->label('View Referrals')
                    ->url("/affiliate/referrals")
                    ->openUrlInNewTab(),
            ])
            ->sendToDatabase($recepient);
            LaravelNotification::send($recepient, new ReferralRejected());
        }
    }

    /**
     * Handle the Referral "deleted" event.
     */
    public function deleted(Referral $referral): void
    {
        //
    }

    /**
     * Handle the Referral "restored" event.
     */
    public function restored(Referral $referral): void
    {
        //
    }

    /**
     * Handle the Referral "force deleted" event.
     */
    public function forceDeleted(Referral $referral): void
    {
        //
    }
}
