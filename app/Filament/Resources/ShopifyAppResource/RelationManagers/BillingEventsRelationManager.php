<?php

namespace App\Filament\Resources\ShopifyAppResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BillingEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'BillingEvents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
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
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(function ($state) use ($eventTypeMapping) {
                        return $eventTypeMapping[$state] ?? $state;
                    }),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(function ($record, $state) {
                        return
                        "
                    <div>$state <span> $record->currency</span></div>
                    ";
                    })->html(),
                Tables\Columns\TextColumn::make('billingOn')->default('No billing Date'),
                Tables\Columns\TextColumn::make('shop.name')->searchable(),
                Tables\Columns\TextColumn::make('isTest')->label('is Test'),
                Tables\Columns\TextColumn::make('occurred_at')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options($eventTypeMapping)
                    ->multiple(),
            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
