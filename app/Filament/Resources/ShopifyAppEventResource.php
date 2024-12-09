<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopifyAppEventResource\Pages;
use App\Models\ShopifyAppEvent;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter as Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ShopifyAppEventResource extends Resource
{
    protected static ?string $model = ShopifyAppEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Events';

    protected static ?string $modelLabel = 'App Events';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('type_label')->label('Type'),
                TextColumn::make('shop.name')->searchable(),
                TextColumn::make('reason'),
                TextColumn::make('description'),
                TextColumn::make('app.name')->searchable(),
                TextColumn::make('occurred_at')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(ShopifyAppEvent::$EVENT_TYPE_MAPPING)
                    ->multiple(),
                SelectFilter::make('app_id')
                    ->relationship('app', 'name')
                    ->label('App'),
                Filter::make('occurred_at')
                    ->label('Occurred At')
                    ->form([
                        DatePicker::make('start_date')->label('Start Date'),
                        DatePicker::make('end_date')->label('End Date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start_date'], fn ($query, $date) => $query->whereDate('occurred_at', '>=', $date))
                            ->when($data['end_date'], fn ($query, $date) => $query->whereDate('occurred_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {

        return $infolist
            ->schema(components: [
                ComponentsSection::make()->schema([
                    TextEntry::make('type_label')->label('Type'),
                    TextEntry::make('shop.name'),
                    TextEntry::make('reason'),
                    TextEntry::make('description'),
                    TextEntry::make('app.name'),
                    TextEntry::make('occurred_at')->date(),
                ]
                ),
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
            'index' => Pages\ListShopifyAppEvents::route('/'),
            'view' => Pages\ViewShopifyAppEvent::route('/{record}'),
            'create' => Pages\CreateShopifyAppEvent::route('/create'),
            'edit' => Pages\EditShopifyAppEvent::route('/{record}/edit'),
        ];
    }
}
