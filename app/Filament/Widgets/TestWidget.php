<?php

namespace App\Filament\Widgets;

use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestWidget extends BaseWidget
{
    use InteractsWithPageFilters;
    protected function getStats(): array
    {
        $partner = Filament::getTenant();
        $startDate = $this->filters['StartDate'];
        $endDate = $this->filters['EndDate'];
        $selectedApp = $this->filters["App"];
        $app = ShopifyApp::find($selectedApp);
        $user_role = auth()->user()->role;
        return [
            $user_role == "admin" ? Stat::make("Users",User::count())
            ->description("Already joined Users")
            ->descriptionIcon("heroicon-m-user-group")
            ->chart([1,3,5,10,20,40]) : null,
            Stat::make("Apps",ShopifyApp::where('partner_id',$partner->id)->count())
            ->description("Existed Shopify Apps")
            ->chart([1,3,5,10,20,40]),
            Stat::make("Events", ShopifyAppEvent::query()
            ->when($app, fn($query) => $query->where('app_id', $app->id))
            ->when($partner, fn($query) => $query->where('partner_id', $partner->id))
            ->when($startDate, fn($query) => $query->whereDate('occurred_at', '>=', $startDate))
            ->when($endDate, fn($query) => $query->whereDate('occurred_at', '<=', $endDate))
            ->count())
            ->description("Shopify Events")
            ->chart([1, 3, 5, 10, 20, 40])

        ];
    }
}
