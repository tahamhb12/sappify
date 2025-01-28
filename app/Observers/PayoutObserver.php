<?php

namespace App\Observers;

use App\Models\Payout;
use App\Notifications\PayoutAccepted;
use App\Notifications\PayoutRejected;
use App\Notifications\PayoutRequest;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Notification as LaravelNotification;


class PayoutObserver
{
    /**
     * Handle the Payout "created" event.
     */
    public function created(Payout $payout): void
    {
        $recepient = $payout->affiliateProgram->partner->user;
        $partner = $payout->affiliateProgram->partner;
        $program_id = $payout->affiliateProgram->id;


        Notification::make()
            ->title('New Payout Request')
            ->body("A new payout has been submitted and needs approval in $partner->name.")
            ->info()
            ->actions([
                Action::make('viewPayout')
                    ->label('View Payouts')
                    ->url("/admin/$partner->id/affiliate-programs/{$program_id}?activeRelationManager=2")
                    ->openUrlInNewTab(),
            ])
            ->sendToDatabase($recepient);
            LaravelNotification::send($recepient, new PayoutRequest($partner->id,$program_id));

    }

    /**
     * Handle the Payout "updated" event.
     */
    public function updated(Payout $payout): void
    {
        $recepient = $payout->user;
        if($payout->status == 'approved'){
            Notification::make()
            ->title('Payout Request Approved')
            ->body("A payout has been approved.")
            ->success()
            ->actions([
                Action::make('viewPayout')
                    ->label('View Payouts')
                    ->url("/affiliate/payouts")
                    ->openUrlInNewTab(),
            ])
            ->sendToDatabase($recepient);
            LaravelNotification::send($recepient, new PayoutAccepted());
        }else if($payout->status == 'rejected'){
            Notification::make()
            ->title('Payout Request Declined')
            ->body("A payout has been Declined.")
            ->danger()
            ->actions([
                Action::make('viewPayout')
                    ->label('View Payouts')
                    ->url("/affiliate/payouts")
                    ->openUrlInNewTab(),
            ])
            ->sendToDatabase($recepient);
            LaravelNotification::send($recepient, new PayoutRejected());
        }
    }

    /**
     * Handle the Payout "deleted" event.
     */
    public function deleted(Payout $payout): void
    {
        //
    }

    /**
     * Handle the Payout "restored" event.
     */
    public function restored(Payout $payout): void
    {
        //
    }

    /**
     * Handle the Payout "force deleted" event.
     */
    public function forceDeleted(Payout $payout): void
    {
        //
    }
}
