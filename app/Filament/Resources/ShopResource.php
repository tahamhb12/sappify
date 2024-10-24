<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ShopResource\Pages;
use App\Filament\Resources\ShopResource\RelationManagers;
use App\Filament\Resources\ShopResource\RelationManagers\AppsRelationManager;
use App\Filament\Resources\ShopResource\RelationManagers\EventsRelationManager;
use App\Filament\Resources\ShopResource\RelationManagers\TransactionEventsRelationManager;
use App\Models\Partner;
use App\Models\Shop;
use App\Models\ShopifyAppEvent;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Actions\Action; // Correct namespace for table actions
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group as ComponentsGroup;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Components\Section as ComponentsSection;
use Illuminate\Http\RedirectResponse;

class ShopResource extends Resource
{
    protected static ?string $model = Shop::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("")->schema([
                    FileUpload::make('avatarUrl')->disk('public')->directory('images')->label("Avatar"),
                    TextInput::make('description')->placeholder('Add Description'),
                    TextInput::make('notes')->placeholder( 'Add Note'),
                    TagsInput::make('tags')->separator(',')
                ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatarUrl')->default('images/shop.png')->label("Avatar"),
                TextColumn::make('name')
                ->label('App')
                ->formatStateUsing(function ($state,$record) {
                    return "<div>
                                <div style=font-weight:bold>
                                $state
                                </div>
                                <div style=font-size:13px>$record->myshopifyDomain</div>
                            </div>";
                })
                ->html()
                ->searchable(),
                TagsColumn::make('tags')->default('No Tags Yet')->label('Tags'),
                TextColumn::make('notes')->default('No notes'),
                TextColumn::make('description')->default('No description'),
                TextColumn::make('status')->default(function ($record) {
                    $type = ShopifyAppEvent::where('shop_id', $record->id)
                        ->where(function ($query) {
                            $query->where('type', 'RELATIONSHIP_INSTALLED')
                                ->orWhere('type', 'RELATIONSHIP_UNINSTALLED');
                        })
                        ->orderBy('occurred_at', 'desc')
                        ->first()
                        ?->type ?? 'N/A';
                    if ($type === 'RELATIONSHIP_INSTALLED') {
                        return 'Installed';
                    } elseif ($type === 'RELATIONSHIP_UNINSTALLED') {
                        return 'Uninstalled';
                    }
                    return 'N/A';
                })->badge()
                ->color(function (string $state){
                    if($state=='Uninstalled') return 'danger';
                    return 'success';
                }),
           ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Action::make('visitLink')
                    ->label('Visit')
                    ->url(fn ($record) => 'https://'.$record->myshopifyDomain)->openUrlInNewTab()
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
                ComponentsGroup::make()->schema([
                    ComponentsSection::make('Image')->schema([
                        ImageEntry::make('avatarUrl')->default('images/shop.png')->label("Avatar")
                        ->alignCenter()
                    ])->collapsible(),
                    ComponentsSection::make()->schema([
                        TextEntry::make('name')->label("Store Name"),
                    ]),
                ]),
                ComponentsSection::make()->schema([
                    TextEntry::make('description')->default('No description'),
                    TextEntry::make('notes')->default('No notes'),
                    TextEntry::make('tags')->default('No Tags Yet')->badge(),
                    TextEntry::make('status')->default(function ($record) {
                        $type = ShopifyAppEvent::where('shop_id', $record->id)
                            ->where(function ($query) {
                            $query->where('type', 'RELATIONSHIP_INSTALLED')
                                ->orWhere('type', 'RELATIONSHIP_UNINSTALLED');
                            })
                            ->orderBy('occurred_at', 'desc')
                            ->first()
                            ?->type ?? 'N/A';
                        if ($type === 'RELATIONSHIP_INSTALLED') {
                            return 'Installed';
                        } elseif ($type === 'RELATIONSHIP_UNINSTALLED') {
                            return 'Uninstalled';
                        }
                        return 'N/A';
                        })->badge()
                        ->color(function (string $state){
                            if($state=='Uninstalled') return 'danger';
                            return 'success';
                        }),
                        TextEntry::make('myshopifyDomain')
                        ->label("")
                        ->html()
                        ->formatStateUsing(fn ($state, $record) => '<a href="https://partners.shopify.com/' . $partner->partner_id . '/stores/' . preg_replace('/\D/', '', $record->shop_id) . '" target="_blank" class="text-primary-600 underline">View Store</a>'),
                ])->columnSpan(3)
        ])->columns(4);
    }

    public static function getRelations(): array
    {
        return [
            EventsRelationManager::class,
            AppsRelationManager::class,
            TransactionEventsRelationManager::class
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
