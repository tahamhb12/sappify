<?php

namespace App\Filament\Resources\BillingEventsResource\Pages;

use App\Filament\Resources\BillingEventsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBillingEvents extends ListRecords
{
    protected static string $resource = BillingEventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
