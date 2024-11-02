<?php

namespace App\Http\Controllers;

use App\Models\BillingEvents;
use App\Models\Partner;
use App\Models\Shop;
use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use App\Services\ApiServices;
use App\Services\ShopUrlData;
use App\Services\UrLdata;
use Filament\Facades\Filament;
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

    public function App($id='145227776001'){
        
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
                    $res = $this->apiservices->getData('
                    {
                        app(id: "gid://partners/App/'.$id.'"){
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
        
                    $app = ShopifyApp::where("app_id", $id)->first();
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
                                'partner_id'=> 1,
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
        

            return $res->json("data.app.events");




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
