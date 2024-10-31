<?php

namespace App\Filament\Resources\ShopifyAppResource\Pages;

use App\Filament\Resources\ShopifyAppResource;
use App\Services\ApiServices;
use App\Models\Partner;
use App\Services\UrLdata;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;

use Illuminate\Support\Facades\Artisan;
use Filament\Resources\Pages\CreateRecord;

class CreateShopifyApp extends CreateRecord
{
    protected static string $resource = ShopifyAppResource::class;


    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $partnerId = Filament::getTenant()->partner_id;
        $appId = $data['app_id'];

        $getApp = Artisan::call('app:sync-partner-app', [
            'partnerId' => $partnerId,
            'appId' => $appId,
        ]);
        $apiKey = trim(Artisan::output());

        if ($getApp == 1) {
            Notification::make()
                ->title('Failed to sync the app. App info incorrect')
                ->danger()
                ->send();
            $this->halt();
        } elseif ($data['api_key'] !== $apiKey) {
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
            'partnerId' => $partnerId,
            'appId' => $appId,
        ]);
        return $record;
    }

    protected function afterCreate(): void
    {
        $urldata = new UrLdata();
        $url = $this->form->getState()['url'];
        $getData = $urldata->getUrlData($url);
        if($getData && $getData!=='bad link'){
            $this->record->title=$getData['title'];
            $this->record->description=$getData['description'];
            $this->record->image=$getData['image'];
            $this->record->save();
        }else if(!$getData){
            '';
        }else{
            Notification::make()
            ->title('bad url.')
            ->danger()
            ->send();
        }
    }
}
