<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use App\Models\Shop;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected function afterCreate(): void
    {
        $shopIds = $this->form->getState()['shop_ids'];

        if ($shopIds && is_array($shopIds)) {
            Shop::whereIn('id', $shopIds)->update(['company_id' => $this->record->id]);
        }
    }
}
