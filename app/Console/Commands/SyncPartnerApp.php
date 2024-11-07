<?php

namespace App\Console\Commands;

use App\Models\Partner;
use App\Models\ShopifyApp;
use App\Services\ApiServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class SyncPartnerApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-partner-app {partnerId} {appId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the ShopifyApp';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $partnerId = intval($this->argument('partnerId'));
        $appId = intval($this->argument('appId'));

//     $this->info(string: "Sync AppId $appId in Partner $partnerId");

        $partner = Partner::where("partner_id",$partnerId)->first();
        if (!$partner) {
            $this->error("partner not found");
            return 1;
        }

        $api = new ApiServices($partner);
        $response = $api->getApp($appId);


        $appData = $response->json('data');
        if($appData && $appData['app']!==null){
            $this->info($appData['app']['apiKey']);
        }else{
            return 1;
        }
    }
}
