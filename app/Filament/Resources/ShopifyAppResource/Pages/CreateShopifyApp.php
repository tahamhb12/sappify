<?php

namespace App\Filament\Resources\ShopifyAppResource\Pages;

use App\Filament\Resources\ShopifyAppResource;
use App\Services\ApiServices;
use App\Models\Partner;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;

use Illuminate\Support\Facades\Artisan;
use Filament\Resources\Pages\CreateRecord;

class CreateShopifyApp extends CreateRecord
{
    protected static string $resource = ShopifyAppResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $record = parent::handleRecordCreation($data);

        $partnerId = Filament::getTenant()->partner_id;
        $appId = $data['app_id'];

        $cmd = Artisan::call('app:sync-partner-app-events', [
            'partnerId' => $partnerId,
            'appId' => $appId,
        ]);

        if($cmd == 1) {
            Notification::make()
            ->title('Failed to sync the app. App info incorrect')
            ->danger()
            ->send();
            $record->delete();
            // stop the creation process
            $this->halt();
            } else {
            Notification::make()
                ->title('App and events synced and created successfully.')
                ->success()
                ->send();
            return $record;
        }
    }
}
