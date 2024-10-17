<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopifyAppResource\Pages;
use App\Filament\Resources\ShopifyAppResource\RelationManagers;
use App\Models\ShopifyApp;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
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

class ShopifyAppResource extends Resource
{
    protected static ?string $model = ShopifyApp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('app_id')->required(),
                TextInput::make('name')->required(),
                TextInput::make('api_key')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('app_id')->label("id")->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('api_key'),
                TextColumn::make('partner.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListShopifyApps::route('/'),
            'create' => Pages\CreateShopifyApp::route('/create'),
            'edit' => Pages\EditShopifyApp::route('/{record}/edit'),
        ];
    }
}
