<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use App\Services\ApiServices;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Http;

class ShopifyAppEventController extends Controller
{

    protected $responseData;
    protected $apiservices;

    public function __construct(){
        $partner = Partner::first();
        $this->apiservices = new ApiServices($partner);
    }
    public function Events(){
        $response = $this->apiservices->getEvents("157471866881");
        $shops = Shop::all();
        $this->responseData = $shops[0]["shop_id"];
        return $this->responseData;
    }

    public function store(){
        $this->Events();
        $eventsData = $this->responseData;

        for($i = 0; $i < count($eventsData); $i++){
            $event = ShopifyAppEvent::create([
                'occurred_at' => $eventsData[$i]["node"]["occurredAt"],
                'type'=> $eventsData[$i]["node"]['type'],
                'shopify_app_id'=> "2",
                'shop_id'=> "1"
            ]);
        }


    return response()->json($event, 201);
    }
}

