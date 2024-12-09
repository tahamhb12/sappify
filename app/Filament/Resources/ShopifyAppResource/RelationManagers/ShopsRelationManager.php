<?php

namespace App\Filament\Resources\ShopifyAppResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ShopsRelationManager extends RelationManager
{
    protected static string $relationship = 'shops';

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
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->default('images/shop.png')
                    ->label('Avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->description(fn ($record) => $record->myshopifyDomain)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags')->default('No Tags Yet')->label('Tags')->badge(),
                Tables\Columns\TextColumn::make('notes')->default('No notes'),
                Tables\Columns\TextColumn::make('description')->default('No description')->limit(19),
                Tables\Columns\TextColumn::make('pivot.status')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'RELATIONSHIP_INSTALLED':
                                return 'Installed';
                            case 'RELATIONSHIP_UNINSTALLED':
                                return 'Uninstalled';
                            case 'RELATIONSHIP_DEACTIVATED':
                                return 'Deactivated';
                            case 'RELATIONSHIP_REACTIVATED':
                                return 'Reactivated';
                            default:
                                return 'Unknown';
                        }
                    })
                    ->badge()
                    ->color(fn ($state) => $state == 'RELATIONSHIP_UNINSTALLED' ? 'danger' : ($state == 'RELATIONSHIP_INSTALLED' ? 'success' : 'warning')),
            ])
            ->filters([
                //
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
