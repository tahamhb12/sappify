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

    public function App()
    {
        $app = ShopifyApp::where('app_id', '145227776001')->first();
        $res = $this->apiservices->getData('
                {
                    app(id: "gid://partners/App/'.$app->app_id.'"){
                        events(types:[SUBSCRIPTION_CHARGE_EXPIRED]) {
                        edges {
                            cursor
                            node {
                            occurredAt
                            type
                            shop {
                                avatarUrl
                                id
                                myshopifyDomain
                                name
                            }
                            ... on SubscriptionChargeExpired {
                                charge  {
                                amount{
                                    amount
                                    currencyCode
                                }
                                billingOn
                                id
                                name
                                test
                                }
                            }
                            }
                        }
                        pageInfo {
                            hasNextPage
                            hasPreviousPage
                        }
                        }
                    }
                    }');

        return $res->json();
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
