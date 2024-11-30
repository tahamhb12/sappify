<?php

namespace App\Filament\Resources\AffiliateProgramResource\Pages;

use App\Filament\Resources\AffiliateProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAffiliateProgram extends EditRecord
{
    protected static string $resource = AffiliateProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
