<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Shop;
use App\Models\ShopifyApp;
use App\Services\ApiServices;
use App\Services\ShopUrlData;
use App\Services\UrLdata;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\json;

class ShopifyAppController extends Controller
{
    protected $responseData;
    protected $apiservices;

    public function __construct(){
        $partner = Partner::first();
        $this->apiservices = new ApiServices($partner);
    }

    public function App(){
/*         $shop = Shop::find(80);  // Replace with your shop's ID
        // Get related apps
        $apps = $shop->apps; */

        return $this->apiservices->getData('query {
  transactions {
    edges {
      node {
        id
        createdAt
        ... on ReferralTransaction {
          shop {
            id
            name
            myshopifyDomain
          }
            amount{
              amount
              currencyCode
            }
            category
         }
      }
    }
  }
}')->json();
/*         return response()->json($this->responseData);
 */    }
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
