<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Shop;
use App\Models\ShopifyAppEvent;
use App\Services\ApiServices;

class ShopifyAppEventController extends Controller
{
    protected $responseData;

    protected $apiservices;

    public function __construct()
    {
        $partner = Partner::first();
        $this->apiservices = new ApiServices($partner);
    }

    public function Events()
    {
        $response = $this->apiservices->getEvents('157471866881');
        $shops = Shop::all();
        $this->responseData = $response->json();

        return $this->responseData;
    }

    public function store()
    {
        $this->Events();
        $eventsData = $this->responseData;

        for ($i = 0; $i < count($eventsData); $i++) {
            $event = ShopifyAppEvent::create([
                'occurred_at' => $eventsData[$i]['node']['occurredAt'],
                'type' => $eventsData[$i]['node']['type'],
                'shopify_app_id' => '2',
                'shop_id' => '1',
            ]);
        }

        return response()->json($event, 201);
    }
}
