<?php

namespace App\Filament\Affiliate\Resources\ReferralResource\Pages;

use App\Filament\Affiliate\Resources\ReferralResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReferral extends ViewRecord
{
    protected static string $resource = ReferralResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
