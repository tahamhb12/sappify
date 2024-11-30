<?php

namespace App\Filament\Resources\AffiliateProgramResource\Pages;

use App\Filament\Resources\AffiliateProgramResource;
use App\Models\ShopifyApp;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateAffiliateProgram extends CreateRecord
{
    protected static string $resource = AffiliateProgramResource::class;

    protected function afterCreate(): void
    {
        $app_id = $this->form->getState()['app_id'];
        $unique_id = Str::uuid();
        $base_url = config('app.url');
        $this->record->unique_id = $unique_id;
        $this->record->sign_up_page = ''. $base_url.'/affiliate/request/' . $app_id . '/' . $unique_id;
        $this->record->save();
    }

}
