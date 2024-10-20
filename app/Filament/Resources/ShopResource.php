<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ShopResource\Pages;
use App\Filament\Resources\ShopResource\RelationManagers;
use App\Filament\Resources\ShopResource\RelationManagers\AppsRelationManager;
use App\Filament\Resources\ShopResource\RelationManagers\EventsRelationManager;
use App\Models\Partner;
use App\Models\Shop;
use Filament\Facades\Filament;
use Filament\Tables\Actions\Action; // Correct namespace for table actions
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Components\Section as ComponentsSection;
use Illuminate\Http\RedirectResponse;

class ShopResource extends Resource
{
    protected static ?string $model = Shop::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('shop_id'),
                TextInput::make('avatarUrl'),
                TextInput::make('name'),
                TextInput::make('myshopifyDomain'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('avatarUrl'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('myshopifyDomain')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Action::make('visitLink')
                    ->label('Visit')
                    ->url(fn ($record) => 'https://'.$record->myshopifyDomain)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        $partner = Filament::getTenant();

        return $infolist
        ->schema(components: [
            ComponentsSection::make()->schema([
                TextEntry::make('avatarUrl'),
                TextEntry::make('name')->label("Store Name"),
                TextEntry::make('myshopifyDomain')
                ->label("")
                ->html()
                ->formatStateUsing(fn ($state, $record) => '<a href="https://partners.shopify.com/' . $partner->partner_id . '/stores/' . preg_replace('/\D/', '', $record->shop_id) . '" target="_blank" class="text-primary-600 underline">View Store</a>'),
            ])
        ]);
    }

    public static function getRelations(): array
    {
        return [
            EventsRelationManager::class,
            AppsRelationManager::class
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
