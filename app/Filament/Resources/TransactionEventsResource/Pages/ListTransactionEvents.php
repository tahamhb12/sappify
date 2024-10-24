<?php

namespace App\Filament\Resources\TransactionEventsResource\Pages;

use App\Filament\Resources\TransactionEventsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactionEvents extends ListRecords
{
    protected static string $resource = TransactionEventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
