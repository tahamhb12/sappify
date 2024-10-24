<?php

namespace App\Filament\Resources\TransactionEventsResource\Pages;

use App\Filament\Resources\TransactionEventsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransactionEvents extends EditRecord
{
    protected static string $resource = TransactionEventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
