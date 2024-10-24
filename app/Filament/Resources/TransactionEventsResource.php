<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionEventsResource\Pages;
use App\Filament\Resources\TransactionEventsResource\RelationManagers;
use App\Models\ShopifyApp;
use App\Models\TransactionEvent;
use App\Models\TransactionEvents;
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


class TransactionEventsResource extends Resource
{
    protected static ?string $model = TransactionEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "Events";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {

        $eventTypeMapping = [
            'CREDIT_APPLIED' => 'Credit Applied',
            'CREDIT_FAILED' => 'Credit Failed',
            'CREDIT_PENDING' => 'Credit Pending',
            'ONE_TIME_CHARGE_ACCEPTED' => 'One-Time Charge Accepted',
            'ONE_TIME_CHARGE_ACTIVATED' => 'One-Time Charge Activated',
            'ONE_TIME_CHARGE_DECLINED' => 'One-Time Charge Declined',
            'ONE_TIME_CHARGE_EXPIRED' => 'One-Time Charge Expired',
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

        return $table
            ->columns([
                TextColumn::make('type')
                ->formatStateUsing(function ($state) use ($eventTypeMapping) {
                    return $eventTypeMapping[$state] ?? $state;
                })
                ->searchable(),
                TextColumn::make('app.name')->searchable(),
                TextColumn::make('shop.name')->searchable(),
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make(Auth::check() && Auth::user()->role == "admin"
                ?[
                    Tables\Actions\DeleteBulkAction::make(),
                ]:[]
            ),
            ]);
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
                    return $eventTypeMapping[$state] ?? $state;  // Use simplified labels
                }),
                TextEntry::make('app.name'),
                TextEntry::make('shop.name'),
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
            'index' => Pages\ListTransactionEvents::route('/'),
            'create' => Pages\CreateTransactionEvents::route('/create'),
            'view' => Pages\ViewTransactionEvents::route('/{record}'),
            'edit' => Pages\EditTransactionEvents::route('/{record}/edit'),
        ];
    }
}
