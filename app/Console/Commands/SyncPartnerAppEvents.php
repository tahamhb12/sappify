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
    protected $description = 'Fetch Shopify App Events and Billing Events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $partnerId = $this->argument('partnerId');
        $appId = $this->argument('appId');

        $partner = Partner::where( "partner_id",$partnerId)->first();
        $ShopifyApp = ShopifyApp::where( "app_id",$appId)->first();

        $api = new ApiServices($partner);
        $api->getEvents($ShopifyApp);

    }
}
