<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopifyAppEventResource\Pages;
use App\Filament\Resources\ShopifyAppEventResource\RelationManagers;
use App\Models\Shop;
use App\Models\ShopifyApp;
use App\Models\ShopifyAppEvent;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ShopifyAppEventResource extends Resource
{
    protected static ?string $model = ShopifyAppEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $modelLabel = 'Events';


    public static function form(Form $form): Form
    {
        $types =[
            'CREDIT_APPLIED',
            'CREDIT_FAILED',
            'CREDIT_PENDING',
            'ONE_TIME_CHARGE_ACCEPTED',
            'ONE_TIME_CHARGE_ACTIVATED',
            'ONE_TIME_CHARGE_DECLINED',
            'ONE_TIME_CHARGE_EXPIRED',
            'RELATIONSHIP_DEACTIVATED',
            'RELATIONSHIP_INSTALLED',
            'RELATIONSHIP_REACTIVATED',
            'RELATIONSHIP_UNINSTALLED',
            'SUBSCRIPTION_APPROACHING_CAPPED_AMOUNT',
            'SUBSCRIPTION_CAPPED_AMOUNT_UPDATED',
            'SUBSCRIPTION_CHARGE_ACCEPTED',
            'SUBSCRIPTION_CHARGE_ACTIVATED',
            'SUBSCRIPTION_CHARGE_CANCELED',
            'SUBSCRIPTION_CHARGE_DECLINED',
            'SUBSCRIPTION_CHARGE_EXPIRED',
            'SUBSCRIPTION_CHARGE_FROZEN',
            'SUBSCRIPTION_CHARGE_UNFROZEN',
            'USAGE_CHARGE_APPLIED'
        ];
        return $form
            ->schema([
                Select::make('app_id')->relationship("app","app_id"),
                Select::make('shop_id')->relationship("shops","shop_id"),
                Select::make('type')->options($types),
                TextInput::make('occurred_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable()->sortable(),
                TextColumn::make('type')->searchable(),
                TextColumn::make('app_id'),
                TextColumn::make('shop_id'),
                TextColumn::make('occurred_at')->date()->sortable(),
            ])
            ->filters([
                //
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
            'create' => Pages\CreateShopifyAppEvent::route('/create'),
            'edit' => Pages\EditShopifyAppEvent::route('/{record}/edit'),
        ];
    }
}
