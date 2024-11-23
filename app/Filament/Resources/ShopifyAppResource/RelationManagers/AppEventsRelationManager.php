<?php

namespace App\Filament\Resources\ShopifyAppResource\RelationManagers;

use App\Models\ShopifyAppEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AppEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'AppEvents';

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

        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('type_label')->label('Type'),
                Tables\Columns\TextColumn::make('shop.name')->searchable(),
                Tables\Columns\TextColumn::make('reason')->default('No reason'),
                Tables\Columns\TextColumn::make('description')->default('No description'),
                Tables\Columns\TextColumn::make('occurred_at')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(ShopifyAppEvent::$EVENT_TYPE_MAPPING)
                    ->multiple(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
