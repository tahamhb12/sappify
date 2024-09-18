<?php

namespace App\Console\Commands;

use App\Models\Partner;
use App\Models\Shop;
use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use App\Services\ApiServices;
use Illuminate\Console\Command;
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

        $this->info("Sync AppId $appId in Partner $partnerId");

        $partner = Partner::find( $partnerId)->first();
        if (!$partner) {
            return $this->error("partner not found");
        }
        $app = ShopifyApp::where("app_id", $appId)->first();
        if (!$app) {
            return $this->error("app not found");
        }

        $api = new ApiServices($partner);
        $response = $api->getEvents($appId);

        $eventsData = $response->json("data.app.events.edges");
        $shopData = $response->json("data.app.events.edges");


        for($i = 0; $i < count($eventsData); $i++){

            $shop_id = $shopData[$i]["node"]["shop"]["id"];

            $shop =  Shop::firstOrCreate(([
                'shop_id' => $shop_id,
                "avatarUrl"=>"later",
                "myshopifyDomain"=>$shopData[$i]["node"]["shop"]["myshopifyDomain"],
                "name"=>$shopData[$i]["node"]["shop"]["name"]
            ]));
            $event = ShopifyAppEvent::create([
                'occurred_at' => $eventsData[$i]["node"]["occurredAt"],
                'type'=> $eventsData[$i]["node"]['type'],
                'app_id'=> $app->app_id,
                'shop_id'=> $shop->shop_id
            ]);
        }

        $this->info($response);


}
}
