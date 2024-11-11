<?php

namespace App\Services;

use App\Models\BillingEvents;
use App\Models\Partner;
use App\Models\Shop;
use App\Models\ShopifyAppEvent;
use Illuminate\Support\Facades\Http;

class ApiServices
{
    private $apiUrl;

    private $accessToken;

    private $partnerId;

    public function __construct(Partner $partner, $version = '2024-10')
    {
        $this->apiUrl = 'https://partners.shopify.com/'.$partner->partner_id.'/api/'.$version.'/graphql.json';
        $this->accessToken = $partner->api_key;
        $this->partnerId = $partner->id;
    }

    public function getData($query)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $this->accessToken,
        ])->post($this->apiUrl, [
            'query' => $query,
        ]);

        return $response;
    }

    public function getApp($id)
    {
        return $this->getData('
        {
                app(id: "gid://partners/App/'.$id.'") {
                    id
                    apiKey
                    name
                }
            }');
    }

    public function getAppEvents($ShopifyApp)
    {
        $types = [
            'RELATIONSHIP_DEACTIVATED' => 'RelationshipDeactivated',
            'RELATIONSHIP_INSTALLED' => 'RelationshipInstalled',
            'RELATIONSHIP_REACTIVATED' => 'RelationshipReactivated',
            'RELATIONSHIP_UNINSTALLED' => 'RelationshipUninstalled',
        ];

        foreach ($types as $type => $mode) {
            $hasNextPage = true;
            $endCursor = null;
            while ($hasNextPage) {
                $res = $this->getData('
                {
                    app(id: "gid://partners/App/'.$ShopifyApp->app_id.'"){
                        events(types:['.$type.']'.($endCursor ? ', after: "'.$endCursor.'"' : '').') {
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
                            ... on '.$mode.' {
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
                $UrlData = new ShopUrlData;
                if ($res->json('data.app.events.edges')) {
                    $size = count($res->json('data.app.events.edges'));
                    for ($i = 0; $i < $size; $i++) {

                        $data = $res->json("data.app.events.edges.$i.node");
                        $Shop = collect([
                            'ShopId' => $data['shop']['id'],
                            'ShopAvatar' => $data['shop']['avatarUrl'],
                            'ShopifyDomain' => $data['shop']['myshopifyDomain'],
                            'ShopName' => $data['shop']['name'],
                        ]);
                        $AppEvent = collect([
                            'Type' => $data['type'],
                            'Reason' => $data['reason'] ?? null,
                            'Description' => $data['description'] ?? null,
                            'OccurredAt' => $data['occurredAt'],
                        ]);

                        $LinkData = $UrlData->getUrlData($Shop->get('ShopifyDomain'));
                        $shop = Shop::firstOrCreate(([
                            'shop_id' => $Shop->get('ShopId'),
                            'avatarUrl' => $Shop->get('ShopAvatar'),
                            'myshopifyDomain' => $Shop->get('ShopifyDomain'),
                            'name' => $Shop->get('ShopName'),
                            'partner_id' => $this->partnerId,
                            'title' => $LinkData['title'] ?? null,
                            'image' => $LinkData['image'] ?? null,
                            'description' => $LinkData['description'] ?? null,
                        ]));
                        ShopifyAppEvent::firstOrCreate([
                            'type' => $AppEvent->get('Type'),
                            'reason' => $AppEvent->get('Reason'),
                            'description' => $AppEvent->get('Description'),
                            'app_id' => $ShopifyApp->id,
                            'shop_id' => $shop->id ?? 'no shop',
                            'partner_id' => $this->partnerId,
                            'occurred_at' => $AppEvent->get('OccurredAt'),
                        ]);
                        $shop->apps()->syncWithoutDetaching([$ShopifyApp->id]);
                    }
                    $lastIndex = count($res->json('data.app.events.edges')) - 1;
                    $endCursor = $res->json("data.app.events.edges.$lastIndex.cursor");
                }
                $hasNextPage = $res->json('data.app.events.pageInfo.hasNextPage');
            }
        }
    }

    public function getBillingEvents($ShopifyApp)
    {
        $types = [
            'CREDIT_APPLIED' => 'CreditApplied',
            'CREDIT_FAILED' => 'CreditFailed',
            'CREDIT_PENDING' => 'CreditPending',
            'ONE_TIME_CHARGE_ACCEPTED' => 'OneTimeChargeAccepted',
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
            'SUBSCRIPTION_CHARGE_UNFROZEN' => 'SubscriptionChargeUnfrozen',
            'USAGE_CHARGE_APPLIED' => 'UsageChargeApplied',
        ];

        foreach ($types as $type => $mode) {
            $hasNextPage = true;
            $endCursor = null;
            while ($hasNextPage) {
                $res = $this->getData('
                {
                    app(id: "gid://partners/App/'.$ShopifyApp->app_id.'"){
                        events(types:['.$type.']'.($endCursor ? ', after: "'.$endCursor.'"' : '').') {
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
                                '.(substr($type, 0, 6) === 'CREDIT' ? 'appCredit' : 'charge').' {
                                amount{
                                    amount
                                    currencyCode
                                }
                                '.(substr($mode, 0, 12) === 'Subscription' ? 'billingOn' : '').'
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

                $UrlData = new ShopUrlData;
                if ($res->json('data.app.events.edges')) {
                    $size = count($res->json('data.app.events.edges'));
                    for ($i = 0; $i < $size; $i++) {

                        $data = $res->json("data.app.events.edges.$i.node");
                        $Shop = collect([
                            'ShopId' => $data['shop']['id'],
                            'ShopAvatar' => $data['shop']['avatarUrl'],
                            'ShopifyDomain' => $data['shop']['myshopifyDomain'],
                            'ShopName' => $data['shop']['name'],
                        ]);
                        $BillingEvent = collect([
                            'EventId' => $data['charge']['id'],
                            'Type' => $data['type'],
                            'Amount' => $data['charge']['amount']['amount'],
                            'Currency' => $data['charge']['amount']['currencyCode'],
                            'BillingOn' => substr($type, 0, 12) === 'SUBSCRIPTION' ? $data['charge']['billingOn'] : null,
                            'Name' => $data['charge']['name'],
                            'IsTest' => $data['charge']['test'],
                            'OccurredAt' => $data['occurredAt'],
                        ]);

                        $LinkData = $UrlData->getUrlData($Shop->get('ShopifyDomain'));
                        $shop = Shop::firstOrCreate(([
                            'shop_id' => $Shop->get('ShopId'),
                            'avatarUrl' => $Shop->get('ShopAvatar'),
                            'myshopifyDomain' => $Shop->get('ShopifyDomain'),
                            'name' => $Shop->get('ShopName'),
                            'partner_id' => $this->partnerId,
                            'title' => $LinkData['title'] ?? null,
                            'image' => $LinkData['image'] ?? null,
                            'description' => $LinkData['description'] ?? null,
                        ]));
                        BillingEvents::firstOrCreate([
                            'event_id' => $BillingEvent->get('EventId'),
                            'type' => $BillingEvent->get('Type'),
                            'amount' => $BillingEvent->get('Amount'),
                            'currency' => $BillingEvent->get('Currency'),
                            'billingOn' => $BillingEvent->get('BillingOn'),
                            'name' => $BillingEvent->get('Name'),
                            'isTest' => $BillingEvent->get('IsTest'),
                            'app_id' => $ShopifyApp->id,
                            'partner_id' => $this->partnerId,
                            'shop_id' => $shop->id ?? 'no shop',
                            'occurred_at' => $BillingEvent->get('OccurredAt'),
                        ]);
                        $shop->apps()->syncWithoutDetaching([$ShopifyApp->id]);
                    }
                    $lastIndex = count($res->json('data.app.events.edges')) - 1;
                    $endCursor = $res->json("data.app.events.edges.$lastIndex.cursor");
                }
                $hasNextPage = $res->json('data.app.events.pageInfo.hasNextPage');
            }
        }
    }

    public function getEvents($ShopifyApp)
    {
        $this->getAppEvents($ShopifyApp);
        $this->getBillingEvents($ShopifyApp);
    }

    public function checkPartner()
    {
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
