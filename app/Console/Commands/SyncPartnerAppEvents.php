<?php

namespace App\Console\Commands;

use App\Models\Partner;
use App\Models\Shop;
use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use App\Models\TransactionEvent;
use App\Models\TransactionEvents;
use App\Services\ApiServices;
use App\Services\ShopUrlData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Exists;
use PhpParser\Node\Expr\New_;

class SyncPartnerAppEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-partner-app-events {partnerId} {appId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $partnerId = $this->argument('partnerId');
        $appId = $this->argument('appId');

        $partner = Partner::where( "partner_id",$partnerId)->first();
        $app = ShopifyApp::where("app_id", $appId)->first();


        $api = new ApiServices($partner);
        $response = $api->getEvents($appId);

        $appEvents = [
            'RELATIONSHIP_DEACTIVATED',
            'RELATIONSHIP_INSTALLED',
            'RELATIONSHIP_REACTIVATED',
            'RELATIONSHIP_UNINSTALLED',
        ];

        $urldata = new ShopUrlData();
        $eventsData = $response->json("data.app.events.edges");
        $shopData = $response->json("data.app.events.edges");

        if($eventsData){
            for($i = 0; $i < count($eventsData); $i++){
                $shop_id = $shopData[$i]["node"]["shop"]["id"];
                $shop_avatar = $shopData[$i]["node"]["shop"]["avatarUrl"];

                $data = $urldata->getUrlData($shopData[$i]["node"]["shop"]["myshopifyDomain"]);

                $shop =  Shop::firstOrCreate(([
                    'shop_id' => $shop_id,
                    "avatarUrl"=> $shop_avatar,
                    "myshopifyDomain"=>$shopData[$i]["node"]["shop"]["myshopifyDomain"],
                    "name"=>$shopData[$i]["node"]["shop"]["name"],
                    'partner_id'=> $partner->id,
                    'title'=> $data['title'] ?? null,
                    'image'=> $data['image'] ?? null,
                    'description'=> $data['description'] ?? null
                ]));
                    if(in_array($eventsData[$i]["node"]['type'],$appEvents)){
                        $event = ShopifyAppEvent::firstOrCreate([
                            'occurred_at' => $eventsData[$i]["node"]["occurredAt"],
                            'type'=> $eventsData[$i]["node"]['type'],
                            'app_id'=> $app->id,
                            'shop_id'=> $shop->id,
                            'partner_id'=> $partner->id
                        ]);
                    }else{
                        $event = TransactionEvent::firstOrCreate([
                            'occurred_at' => $eventsData[$i]["node"]["occurredAt"],
                            'type'=> $eventsData[$i]["node"]['type'],
                            'app_id'=> $app->id,
                            'shop_id'=> $shop->id,
                            'partner_id'=> $partner->id
                        ]);
                    }
                $app = ShopifyApp::find($app->id);
                $shop->apps()->syncWithoutDetaching([$app->id]);
            }
        }else{
            return 1;
        }


}
}
