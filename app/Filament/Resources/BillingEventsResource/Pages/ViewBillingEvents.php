<?php

namespace App\Filament\Resources\BillingEventsResource\Pages;

use App\Filament\Resources\BillingEventsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBillingEvents extends ViewRecord
{
    protected static string $resource = BillingEventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
