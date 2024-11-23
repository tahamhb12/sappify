<?php

namespace App\Console\Commands;

use App\Models\Partner;
use App\Models\ShopifyApp;
use App\Services\ApiServices;
use Illuminate\Console\Command;

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
    protected $description = 'Fetch Shopify App Events and Billing Events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $partner_id = $this->argument('partnerId');
        $app_id = $this->argument('appId');

        $partner = Partner::where('partner_id', $partner_id)->first();
        $ShopifyApp = ShopifyApp::where('app_id', $app_id)->first();

        $api = new ApiServices($partner);
        $api->getEvents($ShopifyApp);

    }
}
