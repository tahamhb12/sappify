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
