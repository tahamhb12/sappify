<?php

namespace App\Observers;

use App\Models\AffiliateProgram;
use App\Models\AffiliateUser;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class AffiliateUserObserver
{
    /**
     * Handle the AffiliateUser "created" event.
     */
    public function created(AffiliateUser $affiliateUser): void
    {
        $program = AffiliateProgram::find($affiliateUser->affiliate_program_id);
        $recepient = $program->partner->user;
        Notification::make()
            ->title('New Affiliate Joined')
            ->body("A new Affiliate joined " . $program->app->name)
            ->info()
            ->sendToDatabase($recepient);
    }

    /**
     * Handle the AffiliateUser "updated" event.
     */
    public function updated(AffiliateUser $affiliateUser): void
    {
        //
    }

    /**
     * Handle the AffiliateUser "deleted" event.
     */
    public function deleted(AffiliateUser $affiliateUser): void
    {
        //
    }

    /**
     * Handle the AffiliateUser "restored" event.
     */
    public function restored(AffiliateUser $affiliateUser): void
    {
        //
    }

    /**
     * Handle the AffiliateUser "force deleted" event.
     */
    public function forceDeleted(AffiliateUser $affiliateUser): void
    {
        //
    }
}
