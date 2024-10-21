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
                TextColumn::make('name')
                ->label('App')
                ->formatStateUsing(function ($state) {
                    $name = strtoupper(substr($state, 0, 1));
                    $colorMapping = [
                        'A' => '#FF5733', // Red-Orange
                        'B' => '#33FF57', // Green
                        'C' => '#3357FF', // Blue
                        'D' => '#FF33A1', // Pink
                        'E' => '#33FFA1', // Teal
                        'F' => '#FF33FF', // Magenta
                        'G' => '#FFD133', // Gold
                        'H' => '#FF8C33', // Dark Orange
                        'I' => '#33FF8C', // Light Green
                        'J' => '#FF3333', // Red
                        'K' => '#3366FF', // Light Blue
                        'L' => '#FF33D1', // Light Pink
                        'M' => '#33D1FF', // Light Cyan
                        'N' => '#FFB833', // Light Orange
                        'O' => '#FF5733', // Coral
                        'P' => '#FF33B2', // Fuchsia
                        'Q' => '#33FF57', // Lime Green
                        'R' => '#5733FF', // Indigo
                        'S' => '#FF33C7', // Rose
                        'T' => '#B833FF', // Purple
                        'U' => '#33B8FF', // Sky Blue
                        'V' => '#FF33C7', // Pinkish Purple
                        'W' => '#FFAC33', // Apricot
                        'X' => '#33FF99', // Light Sea Green
                        'Y' => '#FFD700', // Golden Yellow
                        'Z' => '#FF45F0', // Neon Pink   
                    ];
                    $bgColor = $colorMapping[$name]; 
        
                    return "<div style='display: flex; align-items: center;'>
                                <div style='display:flex; justify-content:center; align-items:center; margin-left:-5px; width: 33px; height: 33px; border-radius: 8px; background-color: $bgColor; color: white; font-weight: bold; margin-right: 8px;'>
                                    $name
                                </div>
                                <span>$state</span>
                            </div>";
                })
                ->html()
                ->searchable(),
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
