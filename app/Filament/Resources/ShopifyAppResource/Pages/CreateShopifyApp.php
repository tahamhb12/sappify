<?php

namespace App\Filament\Resources\ShopifyAppResource\Pages;

use App\Filament\Resources\ShopifyAppResource;
use App\Services\UrLdata;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Artisan;

class CreateShopifyApp extends CreateRecord
{
    protected static string $resource = ShopifyAppResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $partner_id = Filament::getTenant()->partner_id;
        $app_id = $data['app_id'];

        $get_app = Artisan::call('app:sync-partner-app', [
            'partnerId' => $partner_id,
            'appId' => $app_id,
        ]);
        $api_key = trim(Artisan::output());

        if ($get_app == 1) {
            Notification::make()
                ->title('Failed to sync the app. App info incorrect')
                ->danger()
                ->send();
            $this->halt();
        } elseif ($data['api_key'] !== $api_key) {
            Notification::make()
                ->title('Failed to sync the app. API key incorrect.')
                ->danger()
                ->send();
            $this->halt();
        }

        $record = parent::handleRecordCreation($data);
        Notification::make()
            ->title('App and events synced and created successfully.')
            ->success()
            ->send();
        Artisan::queue('app:sync-partner-app-events', [
            'partnerId' => $partner_id,
            'appId' => $app_id,
        ]);

        return $record;
    }

    protected function afterCreate(): void
    {
        $url_data = new UrLdata;
        $url = $this->form->getState()['url'];
        $get_data = $url_data->getUrlData($url);
        if ($get_data && $get_data !== 'bad link') {
            $this->record->title = $get_data['title'];
            $this->record->description = $get_data['description'];
            $this->record->image = $get_data['image'];
            $this->record->save();
        } elseif (! $get_data) {

        } else {
            Notification::make()
                ->title('bad url.')
                ->danger()
                ->send();
        }
    }
}
