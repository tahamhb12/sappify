<?php

namespace App\Filament\Resources\AffiliateProgramResource\Pages;

use App\Filament\Resources\AffiliateProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAffiliateProgram extends ViewRecord
{
    protected static string $resource = AffiliateProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
