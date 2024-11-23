<?php

namespace App\Services;

use App\Actions\CreateAppEvent;
use App\Actions\CreateBillingEvent;
use App\Actions\CreateShop;
use App\Models\BillingEvents;
use App\Models\Partner;
use App\Models\Shop;
use App\Models\shopify_app;
use App\Models\ShopifyAppEvent;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Http;

class ApiServices
{
    private $api_url;
    private $access_token;
    private $partner_id;

    public function __construct(Partner $partner,$version = "2024-10")
    {
        $this->api_url = 'https://partners.shopify.com/' . $partner->partner_id . '/api/' . $version . '/graphql.json';
        $this->access_token = $partner->api_key;
        $this->partner_id = $partner->id;
    }
    public function getData($query)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $this->access_token
                ])->post($this->api_url, [
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
    public function getAppEvents($shopify_app)
    {
        $types = [
            'RELATIONSHIP_DEACTIVATED' => 'RelationshipDeactivated',
            'RELATIONSHIP_INSTALLED' => 'RelationshipInstalled',
            'RELATIONSHIP_REACTIVATED' => 'RelationshipReactivated',
            'RELATIONSHIP_UNINSTALLED' => 'RelationshipUninstalled',
        ];

        $last_event_for_shop = [];

        foreach ($types as $type => $mode) {
            $has_next_page = true;
            $end_cursor = null;

            while ($has_next_page) {
                $res = $this->getData('
                {
                    app(id: "gid://partners/App/' . $shopify_app->app_id . '"){
                        events(types:[' . $type . ']' . ($end_cursor ? ', after: "' . $end_cursor . '"' : '') . ') {
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
                                    ' . ($type == 'RELATIONSHIP_UNINSTALLED' ? '
                                    ... on ' . $mode . ' {
                                        reason
                                        description
                                    }' : '') . '
                                }
                            }
                            pageInfo {
                                hasNextPage
                                hasPreviousPage
                            }
                        }
                    }
                }');

                $collected_data = collect($res->json("data.app.events.edges"));

                if ($collected_data) {
                    foreach ($collected_data as $data) {
                        $event_data = $data['node'];
                        $shop_data = $data['node']['shop'];

                        $create_shop = new CreateShop($shop_data, $shopify_app);
                        $shop_id = $create_shop->createShop()->id;

                        $create_app_event = new CreateAppEvent($event_data, $shop_id, $shopify_app);
                        $event = $create_app_event->createAppEvent();

                        if (!isset($last_event_for_shop[$shop_id]) || $last_event_for_shop[$shop_id]['occurred_at'] < $event['occurred_at']){
                            $last_event_for_shop[$shop_id] = $event;
                        }
                    }
                }

                $has_next_page = $res->json("data.app.events.pageInfo.hasNextPage");
                $end_cursor = optional($collected_data->last())["cursor"];
            }
        }
        foreach ($last_event_for_shop as $shop_id => $event) {
            $shop = Shop::find($shop_id);
            $shop->apps()->syncWithoutDetaching([
                $shopify_app->id => ['status' => $event['type']],
            ]);
        }
    }


    public function getBillingEvents($shopify_app){
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
            $has_next_page = true;
            $end_cursor = null;
            while($has_next_page){
                $res = $this->getData('
                {
                    app(id: "gid://partners/App/'.$shopify_app->app_id.'"){
                        events(types:[' . $type . ']' . ($end_cursor ? ', after: "' . $end_cursor . '"' : '') . ') {
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

                    $collected_data = collect($res->json("data.app.events.edges"));
                    if($collected_data){
                        foreach($collected_data as $data){

                            $event_data = $data['node'];
                            $shop_data = $data['node']['shop'];

                            $create_shop = new CreateShop($shop_data,$shopify_app);
                            $shop_id = $create_shop->createShop()->id;

                            $create_billing_event = new CreateBillingEvent($event_data,$shop_id,$shopify_app);
                            $create_billing_event->createBillingEvent();
                        }
                    }
                $has_next_page = $res->json("data.app.events.pageInfo.hasNextPage");
                $end_cursor = optional($collected_data->last())["cursor"];
            }
        }
    }

    public function getEvents($shopify_app){
        $this->getAppEvents($shopify_app);
        $this->getBillingEvents($shopify_app);
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
