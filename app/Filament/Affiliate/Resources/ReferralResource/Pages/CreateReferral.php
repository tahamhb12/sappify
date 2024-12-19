<?php

namespace App\Filament\Affiliate\Resources\ReferralResource\Pages;

use App\Filament\Affiliate\Resources\ReferralResource;
use App\Models\AffiliateProgram;
use App\Models\ShopifyApp;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReferral extends CreateRecord
{
    protected static string $resource = ReferralResource::class;

    public function afterCreate(){

        $program_id = $this->form->getState()['affiliate_program_id'];
        $customer_shop = $this->form->getState()['customer_shop'];
        $program = AffiliateProgram::find($program_id)->first();
        $this->record->partner_id =$program->partner_id;
        $this->record->customer_shop = $customer_shop.'.myshopify.com';
        $this->record->save();
    }
}
