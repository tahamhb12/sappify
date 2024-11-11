<?php

namespace App\Filament\Resources\ShopifyAppEventResource\Pages;

use App\Filament\Resources\ShopifyAppEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShopifyAppEvents extends ListRecords
{
    protected static string $resource = ShopifyAppEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
