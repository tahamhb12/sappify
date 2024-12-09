<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopResource\Pages;
use App\Filament\Resources\ShopResource\RelationManagers\AppEventsRelationManager;
use App\Filament\Resources\ShopResource\RelationManagers\AppsRelationManager;
use App\Filament\Resources\ShopResource\RelationManagers\BillingEventsRelationManager;
use App\Models\Shop;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group as ComponentsGroup;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShopResource extends Resource
{
    protected static ?string $model = Shop::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')->schema([
                    FileUpload::make('avatarUrl')->disk('public')->directory('images')->label('Avatar'),
                    TextInput::make('title')->placeholder('Add title'),
                    TextInput::make('description')->placeholder('Add Description'),
                    TextInput::make('notes')->placeholder('Add Note'),
                    TagsInput::make('tags')->separator(','),
                ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->default('images/shop.png')
                    ->label('Avatar')
                    ->circular(),
                TextColumn::make('name')
                    ->description(fn ($record) => $record->myshopifyDomain)
                    ->searchable(),
                TextColumn::make('tags')->default('No Tags Yet')->label('Tags')->badge(),
                TextColumn::make('notes')->default('No notes'),
                TextColumn::make('description')->default('No description')->limit(19),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Action::make('visitLink')
                    ->label('Visit')
                    ->url(fn ($record) => 'https://'.$record->myshopifyDomain)->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $partnerId = Filament::getTenant()->partner_id;

        return $infolist
            ->schema(components: [
                ComponentsGroup::make()->schema([
                    ComponentsSection::make('Image')->schema([
                        ImageEntry::make('image')->default('images/shop.png')
                            ->label(false)
                            ->size(50)
                            ->width('100%')
                            ->alignCenter(),
                    ])->collapsible(),
                    ComponentsSection::make()->schema([
                        TextEntry::make('name')->label('Store Name')
                            ->label('Store name')
                            ->html()
                            ->formatStateUsing(fn ($state, $record) => '<a href="https://'.$record->myshopifyDomain.'" target="_blank" class="text-primary-600 underline">'.$record->name.'</a>'),
                    ]),
                ]),
                ComponentsSection::make()->schema([
                    TextEntry::make('title')->default('No title'),
                    TextEntry::make('description')->default('No description'),
                    TextEntry::make('notes')->default('No notes'),
                    TextEntry::make('tags')->default('No Tags Yet')->badge(),
                    TextEntry::make('myshopifyDomain')
                        ->label('')
                        ->html()
                        ->formatStateUsing(fn ($state, $record) => '<a href="https://partners.shopify.com/'.$partnerId.'/stores/'.preg_replace('/\D/', '', $record->shop_id).'" target="_blank" class="text-primary-600 underline">View Store</a>'),
                ])->columnSpan(3),
            ])->columns(4);
    }

    public static function getRelations(): array
    {
        return [
            AppEventsRelationManager::class,
            AppsRelationManager::class,
            BillingEventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShops::route('/'),
            'view' => Pages\ViewShop::route('/{record}'),
            'create' => Pages\CreateShop::route('/create'),
            'edit' => Pages\EditShop::route('/{record}/edit'),
        ];
    }
}
