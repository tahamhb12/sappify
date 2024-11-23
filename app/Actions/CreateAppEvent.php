<?php

namespace App\Actions;

use App\Models\ShopifyAppEvent;
use Illuminate\Database\Eloquent\Model;
use Filament\Facades\Filament;


class CreateAppEvent
 {
    private $app_event;
    private $shop_id;
    private $shopify_app;



    public function __construct($data,$shop_id,Model $shopify_app) {
        $this->app_event = $data;
        $this->shopify_app = $shopify_app;
        $this->shop_id = $shop_id;

    }

    public function createAppEvent(){

        $app_event = ShopifyAppEvent::firstOrCreate([
            'type' => $this->app_event['type'],
            'reason' => $this->app_event['reason'] ?? null ,
            'description' => $this->app_event['description'] ?? null,
            'app_id' => $this->shopify_app->id,
            'shop_id' => $this->shop_id,
            'partner_id' => $this->shopify_app->partner_id,
            'occurred_at' => $this->app_event['occurredAt'],
        ]);
        return $app_event;
    }

 }
