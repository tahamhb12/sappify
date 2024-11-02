<?php

namespace App\Services;

use App\Models\BillingEvents;
use App\Models\Partner;
use App\Models\Shop;
use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Http;

class ApiServices
{
    private $apiUrl;
    private $accessToken;
    private $partnerId;

    public function __construct(Partner $partner,$version = "2024-10")
    {
        $this->apiUrl = 'https://partners.shopify.com/' . $partner->partner_id . '/api/' . $version . '/graphql.json';
        $this->accessToken = $partner->api_key;
        $this->partnerId = $partner->id;
    }
    public function getData($query)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $this->accessToken
                ])->post($this->apiUrl, [
            'query' => $query
        ]);
        return $response;
    }


    public function getApp($id){
        return $this->getData('
        {
                app(id: "gid://partners/App/'.$id.'") {
                    id
                    apiKey
                    name
                }
            }');
    }
    public function getAppEvents($AppId){
        $types = [
            'RELATIONSHIP_DEACTIVATED' => 'RelationshipDeactivated',
            'RELATIONSHIP_INSTALLED' =>'RelationshipInstalled',
            'RELATIONSHIP_REACTIVATED' =>'RelationshipReactivated',
            'RELATIONSHIP_UNINSTALLED' =>'RelationshipUninstalled',
        ];


        foreach ($types as $type => $mode) {
            $hasNextPage = true;
            $endCursor = null; 
            while($hasNextPage){
                $res = $this->getData('
                {
                    app(id: "gid://partners/App/'. $AppId .'"){
                        events(types:[' . $type . ']' . ($endCursor ? ', after: "' . $endCursor . '"' : '') . ') {
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
                            '.($type == 'RELATIONSHIP_UNINSTALLED' ? '
                            ... on ' . $mode . ' {
                                reason
                                description
                            }' : '').'
                            }
                        }
                        pageInfo {
                            hasNextPage
                            hasPreviousPage
                            }
                        }
                    }
                }');
                $app = ShopifyApp::where("app_id", $AppId)->first();
                $urldata = new ShopUrlData();
                if($res->json("data.app.events.edges")){
                    $data = $res->json("data.app.events.edges");
                    for($i = 0 ; $i<count($res->json("data.app.events.edges")) ; $i++){

                        $shop_id = $data[$i]["node"]["shop"]["id"];
                        $shop_avatar = $data[$i]["node"]["shop"]["avatarUrl"];
                        $linkdata = $urldata->getUrlData($data[$i]["node"]["shop"]["myshopifyDomain"]);
                        $shop =  Shop::firstOrCreate(([
                            'shop_id' => $shop_id,
                            "avatarUrl"=> $shop_avatar,
                            "myshopifyDomain"=>$data[$i]["node"]["shop"]["myshopifyDomain"],
                            "name"=>$data[$i]["node"]["shop"]["name"],
                            'partner_id'=> $this->partnerId,
                            'title'=> $linkdata['title'] ?? null,
                            'image'=> $linkdata['image'] ?? null,
                            'description'=> $linkdata['description'] ?? null
                        ]));

                        ShopifyAppEvent::firstOrCreate([
                            'type' => $data[$i]['node']['type'],
                            'reason' => $data[$i]['node']['reason'] ?? null,
                            'description' => $data[$i]['node']['description'] ?? null,
                            'app_id' => $app->id,
                            'shop_id' => $shop->id ?? 'no shop',
                            'partner_id' => $this->partnerId,
                            'occurred_at' => $data[$i]["node"]["occurredAt"],
                        ]);
                        $app = ShopifyApp::find($app->id);
                        $shop->apps()->syncWithoutDetaching([$app->id]);
                    } 
                }
                $hasNextPage = $res->json("data.app.events.pageInfo.hasNextPage");
                $hh = count($res->json("data.app.events.edges"))-1;
                $endCursor = $res->json("data.app.events.edges.$hh.cursor"); 
            }
        }
        }


    public function getBillingEvents($AppId){
        $types = [
            'CREDIT_APPLIED' => 'CreditApplied',
            'CREDIT_FAILED' =>'CreditFailed',
            'CREDIT_PENDING' =>'CreditPending',
            'ONE_TIME_CHARGE_ACCEPTED' =>'OneTimeChargeAccepted',
            'ONE_TIME_CHARGE_ACTIVATED' => 'OneTimeChargeActivated',
            'ONE_TIME_CHARGE_DECLINED' => 'OneTimeChargeDeclined',
            'ONE_TIME_CHARGE_EXPIRED' => 'OneTimeChargeExpired',
            'SUBSCRIPTION_APPROACHING_CAPPED_AMOUNT' => 'SubscriptionApproachingCappedAmount',
            'SUBSCRIPTION_CAPPED_AMOUNT_UPDATED' => 'SubscriptionCappedAmountUpdated',
            'SUBSCRIPTION_CHARGE_ACCEPTED' => 'SubscriptionChargeAccepted',
            'SUBSCRIPTION_CHARGE_ACTIVATED' => 'SubscriptionChargeActivated',
            'SUBSCRIPTION_CHARGE_CANCELED' => 'SubscriptionChargeCanceled',
            'SUBSCRIPTION_CHARGE_DECLINED' => 'SubscriptionChargeDeclined',
            'SUBSCRIPTION_CHARGE_EXPIRED' => 'SubscriptionChargeExpired',
            'SUBSCRIPTION_CHARGE_FROZEN' => 'SubscriptionChargeFrozen',
            'SUBSCRIPTION_CHARGE_UNFROZEN'=> 'SubscriptionChargeUnfrozen',
            'USAGE_CHARGE_APPLIED' => 'UsageChargeApplied'
        ];


        foreach ($types as $type => $mode) {
            $hasNextPage = true;
            $endCursor = null; 
            while($hasNextPage){
                $res = $this->getData('
                {
                    app(id: "gid://partners/App/'.$AppId.'"){
                        events(types:[' . $type . ']' . ($endCursor ? ', after: "' . $endCursor . '"' : '') . ') {
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
                            ... on '.$mode.' {
                                '.(substr($type,0,6) === 'CREDIT' ? 'appCredit' : 'charge') .' {
                                amount{
                                    amount
                                    currencyCode
                                }
                                '. (substr($mode, 0, 12) === 'Subscription' ? 'billingOn' : '') .'
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
    
                $app = ShopifyApp::where("app_id", $AppId)->first();
                $urldata = new ShopUrlData();
                $partner = Filament::getTenant();
                if($res->json("data.app.events.edges")){
                    $data = $res->json("data.app.events.edges");
                     for($i = 0 ; $i<count($res->json("data.app.events.edges")) ; $i++){

                        $shop_id = $data[$i]["node"]["shop"]["id"];
                        $shop_avatar = $data[$i]["node"]["shop"]["avatarUrl"];
                        $linkdata = $urldata->getUrlData($data[$i]["node"]["shop"]["myshopifyDomain"]);
                        $shop =  Shop::firstOrCreate(([
                            'shop_id' => $shop_id,
                            "avatarUrl"=> $shop_avatar,
                            "myshopifyDomain"=>$data[$i]["node"]["shop"]["myshopifyDomain"],
                            "name"=>$data[$i]["node"]["shop"]["name"],
                            'partner_id'=> $this->partnerId,
                            'title'=> $linkdata['title'] ?? null,
                            'image'=> $linkdata['image'] ?? null,
                            'description'=> $linkdata['description'] ?? null
                        ]));

                        BillingEvents::firstOrCreate([
                            'event_id' => $data[$i]['node']['charge']["id"],
                            'type' => $data[$i]['node']['type'],
                            'amount' => $data[$i]['node']['charge']['amount']["amount"],
                            'currency' => $data[$i]['node']['charge']["amount"]['currencyCode'],
                            'billingOn' => substr($type, 0, 12) === 'SUBSCRIPTION' ? $data[$i]['node']['charge']["billingOn"] : null,
                            'name' => $data[$i]['node']['charge']["name"],
                            'isTest' => $data[$i]['node']['charge']["test"],
                            'app_id' => $app->id,
                            'shop_id' => $shop->id ?? 'no shop',
                            'occurred_at' => $data[$i]["node"]["occurredAt"],
                        ]);
                        $app = ShopifyApp::find($app->id);
                        $shop->apps()->syncWithoutDetaching([$app->id]);
                    } 
                }
                $hasNextPage = $res->json("data.app.events.pageInfo.hasNextPage");
                $hh = count($res->json("data.app.events.edges"))-1;
                $endCursor = $res->json("data.app.events.edges.$hh.cursor"); 
            }
        }
    }

    public function checkPartner(){
        return $this->getData(
            '{
                transactions(first: 20) {
                    edges {
                    node {
                        id
                        createdAt
                    }
                    }
                }
                }');
    }
}
