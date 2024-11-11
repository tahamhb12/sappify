<?php

namespace App\Filament\Resources\ShopifyAppResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
        $eventTypeMapping = [
            'RELATIONSHIP_DEACTIVATED' => 'App Deactivated',
            'RELATIONSHIP_INSTALLED' => 'App Installed',
            'RELATIONSHIP_REACTIVATED' => 'App Reactivated',
            'RELATIONSHIP_UNINSTALLED' => 'App Uninstalled',
        ];
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                ->formatStateUsing(function ($state) use ($eventTypeMapping) {
                    return $eventTypeMapping[$state] ?? $state;
                }),
                Tables\Columns\TextColumn::make('shop.name')->searchable(),
                Tables\Columns\TextColumn::make('reason')->default('No reason'),
                Tables\Columns\TextColumn::make('description')->default('No description'),
                Tables\Columns\TextColumn::make('occurred_at')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make("type")
                ->options($eventTypeMapping)
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
