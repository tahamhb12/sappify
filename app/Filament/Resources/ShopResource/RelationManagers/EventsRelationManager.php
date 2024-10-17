<?php

namespace App\Filament\Resources\ShopResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
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
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('type')
                ->formatStateUsing(function ($state) use ($eventTypeMapping) {
                    return $eventTypeMapping[$state] ?? $state;  // Use simplified labels
                }),
                Tables\Columns\TextColumn::make('app.name'),
                Tables\Columns\TextColumn::make('shop.name'),
                Tables\Columns\TextColumn::make('occurred_at')->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
