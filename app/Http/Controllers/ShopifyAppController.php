<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\ShopifyApp;
use App\Services\ApiServices;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;



class ShopifyAppController extends Controller
{
    protected $responseData;
    protected $apiservices;

    public function __construct(){
        $partner = Partner::first();
        $this->apiservices = new ApiServices($partner);
    }

    public function App(){
        $response = $this->apiservices->getApp("157471866881");
        $this->responseData = $response->json('data');
        return $this->responseData;
    }
    public function store(){
        $this->App();
        $appData = $this->responseData;
        $app = ShopifyApp::create([
            'name' => $appData['app']['name'],
            'api_key'=> $appData['app']['apiKey'],
            'partner_id'=> "1"
        ]);
        $apps = ShopifyApp::all();
    return response()->json($apps, 201);
    }
}
