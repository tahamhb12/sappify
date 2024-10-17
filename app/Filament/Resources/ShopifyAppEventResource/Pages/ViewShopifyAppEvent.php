<?php

namespace App\Filament\Resources\ShopifyAppEventResource\Pages;

use App\Filament\Resources\ShopifyAppEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewShopifyAppEvent extends ViewRecord
{
    protected static string $resource = ShopifyAppEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
