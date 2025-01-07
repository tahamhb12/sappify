<?php

namespace App\Filament\Affiliate\Resources\PayoutResource\Pages;

use App\Filament\Affiliate\Resources\PayoutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayout extends EditRecord
{
    protected static string $resource = PayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
