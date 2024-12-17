<?php

namespace App\Filament\Affiliate\Resources\ReferralResource\Pages;

use App\Filament\Affiliate\Resources\ReferralResource;
use App\Models\ShopifyApp;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReferral extends CreateRecord
{
    protected static string $resource = ReferralResource::class;

    public function afterCreate(){

        $app_id = $this->form->getState()['app_id'];
        $app = ShopifyApp::find(1)->first();
        $this->record->partner_id =$app->partner->id;
        $this->record->save();
    }
}
