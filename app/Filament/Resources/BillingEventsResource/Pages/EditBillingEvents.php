<?php

namespace App\Filament\Resources\BillingEventsResource\Pages;

use App\Filament\Resources\BillingEventsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBillingEvents extends EditRecord
{
    protected static string $resource = BillingEventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
