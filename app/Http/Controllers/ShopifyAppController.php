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

    public function App(){
        $id='';

        $types = [
            'RELATIONSHIP_DEACTIVATED' => 'RelationshipDeactivated',
            'RELATIONSHIP_INSTALLED' =>'RelationshipInstalled',
            'RELATIONSHIP_REACTIVATED' =>'RelationshipReactivated',
            'RELATIONSHIP_UNINSTALLED' =>'RelationshipUninstalled',
        ];


            $res = $this->apiservices->getData('
            {
                app(id: "gid://partners/App/145227776001"){
                    events(types:[RELATIONSHIP_UNINSTALLED]) {
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
                        ... on  RelationshipUninstalled{
                                reason
                                description
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

            foreach ($types as $type => $mode) {
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

                        ShopifyAppEvent::firstOrCreate([
                            'type' => $data[$i]['node']['type'],
                            'reason' => $data[$i]['node']['reason'],
                            'description' => $data[$i]['node']['description'],
                            'app_id' => $app->id,
                            'shop_id' => $shop->id ?? 'no shop',
                            'occurred_at' => $data[$i]["node"]["occurredAt"],
                        ]);
                        $app = ShopifyApp::find($app->id);
                        $shop->apps()->syncWithoutDetaching([$app->id]);
                    }
                }
            }


return $res->json();


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
