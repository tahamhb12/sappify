<?php

namespace App\Filament\Widgets;

use App\Models\BillingEvents;
use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Cards extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $partner = Filament::getTenant();
        $start_date = $this->filters['StartDate'];
        $end_date = $this->filters['EndDate'];
        $selected_app = $this->filters['App'];
        $user_role = auth()->user()->role;

        return [
            $user_role == 'admin' ? Stat::make('Users', User::count())
                ->description('Already joined Users')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([1, 3, 5, 10, 20, 40]) : null,
            Stat::make('Apps', ShopifyApp::where('partner_id', $partner->id)->count())
                ->description('Existed Shopify Apps')
                ->chart([1, 3, 5, 10, 20, 40]),
            Stat::make('App Events', ShopifyAppEvent::query()
                ->when($selected_app, fn ($query) => $query->where('app_id', $selected_app))
                ->when($partner, fn ($query) => $query->where('partner_id', $partner->id))
                ->when($start_date, fn ($query) => $query->whereDate('occurred_at', '>=', $start_date))
                ->when($end_date, fn ($query) => $query->whereDate('occurred_at', '<=', $end_date))
                ->count())
                ->description('Shopify App Events')
                ->chart([1, 3, 5, 10, 20, 40]),
            Stat::make('Billing Events', BillingEvents::query()
                ->when($selected_app, fn ($query) => $query->where('app_id', $selected_app))
                ->when($partner, fn ($query) => $query->where('partner_id', $partner->id))
                ->when($start_date, fn ($query) => $query->whereDate('occurred_at', '>=', $start_date))
                ->when($end_date, fn ($query) => $query->whereDate('occurred_at', '<=', $end_date))
                ->count())
                ->description('Billing Events')
                ->chart([1, 3, 5, 10, 20, 40]),
        ];
    }
}
