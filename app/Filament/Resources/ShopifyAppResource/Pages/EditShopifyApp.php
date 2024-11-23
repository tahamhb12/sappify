<?php

namespace App\Filament\Resources\ShopifyAppResource\Pages;

use App\Filament\Resources\ShopifyAppResource;
use App\Services\UrLdata;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditShopifyApp extends EditRecord
{
    protected static string $resource = ShopifyAppResource::class;

    protected function afterSave(): void
    {
        $url_data = new UrLdata;
        $url = $this->form->getState()['url'];
        $get_data = $url_data->getUrlData($url);
        if ($get_data && $get_data !== 'bad link') {
            $this->record->title = $get_data['title'];
            $this->record->description = $get_data['description'];
            $this->record->image = $get_data['image'];
            $this->record->save();
        } elseif (!$get_data) {

        } else {
            Notification::make()
                ->title('bad url.')
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
