<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopifyAppResource\Pages;
use App\Filament\Resources\ShopifyAppResource\RelationManagers\AffiliateProgramRelationManager;
use App\Filament\Resources\ShopifyAppResource\RelationManagers\AppEventsRelationManager;
use App\Filament\Resources\ShopifyAppResource\RelationManagers\BillingEventsRelationManager;
use App\Filament\Resources\ShopifyAppResource\RelationManagers\ShopsRelationManager;
use App\Models\ShopifyApp;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group as ComponentsGroup;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Str;

class ShopifyAppResource extends Resource
{
    protected static ?string $model = ShopifyApp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('app_id')->required()->readOnlyOn('edit')->unique(ignoreRecord: true),
                TextInput::make('name')->required(),
                TextInput::make('api_key')->required()->readOnlyon('edit')->unique(ignoreRecord: true),
                TextInput::make('title')->visibleOn('edit'),
                TextInput::make('description')->visibleOn('edit'),
                TextInput::make('url')->label('App Url'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('name')
                    ->label('App')
                    ->searchable(),
                TextColumn::make('api_key'),
                TextColumn::make('description')->default('No Description')->limit(20),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $partner = Filament::getTenant();

        return $infolist
            ->schema(components: [
                ComponentsGroup::make()->schema([
                    ComponentsSection::make('Image')->schema([
                        ImageEntry::make('image')->label('Avatar')
                            ->alignCenter()
                            ->label(false),
                    ])->collapsible(),
                    ComponentsSection::make()->schema([
                        TextEntry::make('name')->label('App Name'),
                    ]),
                ]),
                ComponentsSection::make()->schema([
                    TextEntry::make('title')->default('No title'),
                    TextEntry::make('description')->default('No description'),
                ])->columnSpan(3),
            ])->columns(4);
    }

    public static function getRelations(): array
    {
        return [
            AppEventsRelationManager::class,
            ShopsRelationManager::class,
            BillingEventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShopifyApps::route('/'),
            'create' => Pages\CreateShopifyApp::route('/create'),
            'view' => Pages\ViewShopifyApp::route('/{record}'),
            'edit' => Pages\EditShopifyApp::route('/{record}/edit'),
        ];
    }
}
