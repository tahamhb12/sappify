<?php

namespace App\Filament\Resources\ShopifyAppResource\Pages;

use App\Filament\Resources\ShopifyAppResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShopifyApps extends ListRecords
{
    protected static string $resource = ShopifyAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
