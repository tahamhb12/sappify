<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopifyAppResource\Pages;
use App\Filament\Resources\ShopifyAppResource\RelationManagers\AppEventsRelationManager;
use App\Filament\Resources\ShopifyAppResource\RelationManagers\BillingEventsRelationManager;
use App\Filament\Resources\ShopifyAppResource\RelationManagers\ShopsRelationManager;
use App\Models\ShopifyApp;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Components\Group as ComponentsGroup;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
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
                TextInput::make('app_id')->required()->readOnlyOn('edit')->unique(ignoreRecord:true),
                TextInput::make('name')->required(),
                TextInput::make('api_key')->required()->readOnlyon('edit')->unique(ignoreRecord:true),
                TextInput::make('title')->visibleOn('edit'),
                TextInput::make('description')->visibleOn('edit'),
                TextInput::make('url')->label('App Url'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('App')
                ->formatStateUsing(function ($state,$record) {
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
                    $title = Str::limit($record->title, 30);

                    return $record->image   ?
                    "<div style='display: flex; align-items: center;'>
                    <div style='display:flex; justify-content:center; align-items:center; margin-left:-5px; width: 33px; height: 33px; border-radius: 8px; color: white; font-weight: bold; margin-right: 8px;'>
                        <img src=$record->image>
                    </div>
                    <p style='display:flex; flex-direction: column;'>
                        $state
                        <span style='font-size:13px'>$title</span>
                    </p>
                </div>"
                    :
                            "<div style='display: flex; align-items: center;'>
                                <div style='display:flex; justify-content:center; align-items:center; margin-left:-5px; width: 33px; height: 33px; border-radius: 8px; background-color: $bgColor; color: white; font-weight: bold; margin-right: 8px;'>
                                    $name
                                </div>
                                <p style='display:flex; flex-direction: column;'>
                                    $state
                                    <span style='font-size:13px'>$record->title</span>
                                </p>
                            </div>";
                })
                ->html()
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
                        ImageEntry::make('image')->label("Avatar")
                        ->alignCenter()
                    ])->collapsible(),
                    ComponentsSection::make()->schema([
                        TextEntry::make('name')->label("App Name"),
                    ]),
                ]),
                ComponentsSection::make()->schema([
                    TextEntry::make('title')->default('No title'),
                    TextEntry::make('description')->default('No description'),
                ])->columnSpan(3)
        ])->columns(4);
    }

    public static function getRelations(): array
    {
        return [
            AppEventsRelationManager::class,
            ShopsRelationManager::class,
            BillingEventsRelationManager::class
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
