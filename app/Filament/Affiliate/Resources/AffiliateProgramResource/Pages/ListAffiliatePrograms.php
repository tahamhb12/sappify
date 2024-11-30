<?php

namespace App\Filament\Affiliate\Resources\AffiliateProgramResource\Pages;

use App\Filament\Affiliate\Resources\AffiliateProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAffiliatePrograms extends ListRecords
{
    protected static string $resource = AffiliateProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
