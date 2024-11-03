<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopifyAppEventResource\Pages;
use App\Filament\Resources\ShopifyAppEventResource\RelationManagers;
use App\Models\Shop;
use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter as Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\DateFilter;


class ShopifyAppEventResource extends Resource
{
    protected static ?string $model = ShopifyAppEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = "Events";

    protected static ?string $modelLabel = 'App Events';



    public static function form(Form $form): Form
    {
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

        return $form
            ->schema([
                Select::make('app_id')->relationship("app","app_id"),
                Select::make('shop_id')->relationship("shops","shop_id"),
                Select::make('type')->options($eventTypeMapping),
                TextInput::make('occurred_at'),
            ]);
    }

    public static function table(Table $table): Table
    {

        $eventTypeMapping = [
            'RELATIONSHIP_DEACTIVATED' => 'App Deactivated',
            'RELATIONSHIP_INSTALLED' => 'App Installed',
            'RELATIONSHIP_REACTIVATED' => 'App Reactivated',
            'RELATIONSHIP_UNINSTALLED' => 'App Uninstalled',
        ];

        return $table
            ->columns([
                TextColumn::make('type')
                ->formatStateUsing(function ($state) use ($eventTypeMapping) {
                    return $eventTypeMapping[$state] ?? $state;
                })
                ->searchable(),
                TextColumn::make('shop.name')->searchable(),
                TextColumn::make('reason'),
                TextColumn::make('description'),
                TextColumn::make('app.name')->searchable(),
                TextColumn::make('occurred_at')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make("type")
                ->options($eventTypeMapping)
                ->multiple(),
                SelectFilter::make("app_id")
                ->options(ShopifyApp::all()->pluck('name',"id"))->label("App"),
                Filter::make('occurred_at')
                ->label('Occurred At')
                ->form([
                    DatePicker::make('start_date')->label('Start Date'),
                    DatePicker::make('end_date')->label('End Date'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['start_date'], fn ($query, $date) => $query->whereDate('occurred_at', '>=', $date))
                        ->when($data['end_date'], fn ($query, $date) => $query->whereDate('occurred_at', '<=', $date));
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

     public static function infolist(Infolist $infolist): Infolist{
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
        return $infolist
        ->schema(components: [
            ComponentsSection::make()->schema([
                TextEntry::make('type')
                ->formatStateUsing(function ($state) use ($eventTypeMapping) {
                    return $eventTypeMapping[$state] ?? $state; 
                }),
                TextEntry::make('shop.name'),
                TextEntry::make('reason'),
                TextEntry::make('description'),
                TextEntry::make('app.name'),
                TextEntry::make('occurred_at')->date(),
            ]
            )
        ]);
     }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShopifyAppEvents::route('/'),
            'view' => Pages\ViewShopifyAppEvent::route('/{record}'),
            'create' => Pages\CreateShopifyAppEvent::route('/create'),
            'edit' => Pages\EditShopifyAppEvent::route('/{record}/edit'),
        ];
    }
}
