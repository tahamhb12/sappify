<?php

namespace App\Filament\Affiliate\Resources\AffiliateProgramResource\Pages;

use App\Filament\Affiliate\Resources\AffiliateProgramResource;
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
