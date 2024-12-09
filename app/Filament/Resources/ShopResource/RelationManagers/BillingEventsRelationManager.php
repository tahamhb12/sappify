<?php

namespace App\Filament\Resources\ShopResource\RelationManagers;

use App\Models\BillingEvents;
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

        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('type_label')->label('Type'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('amount')
                    ->money(fn ($record) => $record->currencyCode),
                Tables\Columns\TextColumn::make('billingOn')->default('No billing Date'),
                Tables\Columns\TextColumn::make('app.name'),
                Tables\Columns\TextColumn::make('isTest')->label('is Test'),
                Tables\Columns\TextColumn::make('occurred_at')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(BillingEvents::$EVENT_TYPE_MAPPING)
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
