<?php

namespace App\Filament\Affiliate\Resources\PayoutResource\Pages;

use App\Filament\Affiliate\Resources\PayoutResource;
use App\Models\AffiliateProgram;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreatePayout extends CreateRecord
{
    protected static string $resource = PayoutResource::class;

    public function beforeCreate(){
        $amount = $this->form->getState()['amount'];
        $program_id = $this->form->getState()['affiliate_program_id'];
        $program = AffiliateProgram::find($program_id);
        $min_payout = $program->min_payout;
        if($amount<$min_payout){
            Notification::make()
                ->title("amount must be more than $min_payout")
                ->danger()
                ->send();
            $this->halt();
        }
    }

}
