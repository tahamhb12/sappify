<?php

namespace App\Filament\Resources\ShopifyAppEventResource\Pages;

use App\Filament\Resources\ShopifyAppEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShopifyAppEvent extends EditRecord
{
    protected static string $resource = ShopifyAppEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
