<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Models\ShopifyAppEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
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
                    ->readOnly(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image')->default('images/shop.png')->label('Avatar')->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('App')
                    ->description(fn($record)=>$record->myshopifyDomain)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags')->default('No Tags Yet')->label('Tags')->badge(),
                Tables\Columns\TextColumn::make('notes')->default('No notes'),
                Tables\Columns\TextColumn::make('description')->default('No description')->limit(16),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            Tables\Actions\AssociateAction::make()
            ->preloadRecordSelect()
            ->multiple(),
        ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\DissociateAction::make(),
            ])
            ->bulkActions([

            ]);
    }
}
