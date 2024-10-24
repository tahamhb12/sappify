<?php

namespace App\Filament\Resources\TransactionEventsResource\Pages;

use App\Filament\Resources\TransactionEventsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTransactionEvents extends ViewRecord
{
    protected static string $resource = TransactionEventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
