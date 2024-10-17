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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $partnerId = intval($this->argument('partnerId'));
        $appId = intval($this->argument('appId'));

        $this->info(string: "Sync AppId $appId in Partner $partnerId");

        $partner = Partner::where("partner_id",$partnerId)->first();

        if (!$partner) {
            $this->error("partner not found");
            return 1;
        }

        $api = new ApiServices($partner);

        $response = $api->getApp($appId);

        /// save app in DB

        $appData = $response->json('data');

        $this->info($response);

        $app = ShopifyApp::create([
            'app_id' => preg_replace('/\D/', '', $appData['app']['id']),
            'name' => $appData['app']['name'],
            'api_key'=> $appData['app']['apiKey'],
            'partner_id'=> $partner->id,
        ]);


    }
}
