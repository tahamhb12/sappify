<?php

namespace App\Filament\Widgets;

use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Users",User::count())
            ->description("Already joined Users")
            ->descriptionIcon("heroicon-m-user-group")
            ->chart([1,3,5,10,20,40]),
            Stat::make("Apps",ShopifyApp::count())
            ->description("Existed Shopify Apps")
            ->chart([1,3,5,10,20,40]),
            Stat::make("Events",ShopifyAppEvent::count())
            ->description("ShopifyEvents")
            ->chart([1,3,5,10,20,40]),
        ];
    }
}
