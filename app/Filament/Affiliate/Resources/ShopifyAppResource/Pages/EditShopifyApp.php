<?php

namespace App\Filament\Affiliate\Resources\ShopifyAppResource\Pages;

use App\Filament\Affiliate\Resources\ShopifyAppResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShopifyApp extends EditRecord
{
    protected static string $resource = ShopifyAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
