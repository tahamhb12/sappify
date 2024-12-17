<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\ShopifyApp;
use App\Services\ApiServices;

class ShopifyAppController extends Controller
{
    protected $responseData;

    protected $apiservices;

    public function __construct()
    {
        $partner = Partner::first();
        $this->apiservices = new ApiServices($partner);
    }

    public function test()
    {
        $app_id = 1;
        $app = ShopifyApp::find(1)->first();
        dd($app->partner->id);

    }

    public function store()
    {
        $this->App();
        $appData = $this->responseData;
        $app = ShopifyApp::create([
            'name' => $appData['app']['name'],
            'api_key' => $appData['app']['apiKey'],
            'partner_id' => '1',
        ]);
        $apps = ShopifyApp::all();

        return response()->json($apps, 201);
    }
}
