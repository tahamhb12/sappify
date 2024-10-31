<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Shop\OrderResource;
use App\Models\Shop\Order;
use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Squire\Models\Currency;
use Tables\Columns\TextColumn;

class LatestEvents extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    use InteractsWithPageFilters;



    public function table(Table $table): Table
    {
        $selectedApp = $this->filters["App"];

        $eventTypeMapping = [
            'CREDIT_APPLIED' => 'Credit Applied',
            'CREDIT_FAILED' => 'Credit Failed',
            'CREDIT_PENDING' => 'Credit Pending',
            'ONE_TIME_CHARGE_ACCEPTED' => 'One-Time Charge Accepted',
            'ONE_TIME_CHARGE_ACTIVATED' => 'One-Time Charge Activated',
            'ONE_TIME_CHARGE_DECLINED' => 'One-Time Charge Declined',
            'ONE_TIME_CHARGE_EXPIRED' => 'One-Time Charge Expired',
            'RELATIONSHIP_DEACTIVATED' => 'App Deactivated',
            'RELATIONSHIP_INSTALLED' => 'App Installed',
            'RELATIONSHIP_REACTIVATED' => 'App Reactivated',
            'RELATIONSHIP_UNINSTALLED' => 'App Uninstalled',
            'SUBSCRIPTION_APPROACHING_CAPPED_AMOUNT' => 'Subscription Approaching Cap',
            'SUBSCRIPTION_CAPPED_AMOUNT_UPDATED' => 'Subscription Cap Updated',
            'SUBSCRIPTION_CHARGE_ACCEPTED' => 'Subscription Charge Accepted',
            'SUBSCRIPTION_CHARGE_ACTIVATED' => 'Subscription Activated',
            'SUBSCRIPTION_CHARGE_CANCELED' => 'Subscription Canceled',
            'SUBSCRIPTION_CHARGE_DECLINED' => 'Subscription Charge Declined',
            'SUBSCRIPTION_CHARGE_EXPIRED' => 'Subscription Charge Expired',
            'SUBSCRIPTION_CHARGE_FROZEN' => 'Subscription Frozen',
            'SUBSCRIPTION_CHARGE_UNFROZEN' => 'Subscription Unfrozen',
            'USAGE_CHARGE_APPLIED' => 'Usage Charge Applied',
        ];
        $partner = Filament::getTenant();
        return $table
            ->query(ShopifyAppEvent::query()->when($selectedApp, fn($query) => $query->where('app_id', $selectedApp))
            ->when($partner, fn($query) => $query->where('partner_id', $partner->id))
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('occurred_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make("id"),
                Tables\Columns\TextColumn::make('type')
                ->formatStateUsing(function ($state) use ($eventTypeMapping) {
                    return $eventTypeMapping[$state] ?? $state;
                }),
                Tables\Columns\TextColumn::make("app.name"),
                Tables\Columns\TextColumn::make("shop.name"),
                Tables\Columns\TextColumn::make("occurred_at")->date()->sortable(),
            ]);
    }
}
