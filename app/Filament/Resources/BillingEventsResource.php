<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillingEventsResource\Pages;
use App\Models\BillingEvents;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter as Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BillingEventsResource extends Resource
{
    protected static ?string $model = BillingEvents::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Events';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('type_label')->label('Type'),
                TextColumn::make('name'),
                TextColumn::make('shop.name'),
                TextColumn::make('app.name')->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money(fn ($record) => $record->currencyCode),
                TextColumn::make('billingOn')->default('No billing Date'),
                IconColumn::make('isTest')->label('is Test')->boolean(),
                TextColumn::make('occurred_at')->date(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(BillingEvents::$EVENT_TYPE_MAPPING)
                    ->multiple(),
                SelectFilter::make('isTest')
                    ->options(['false', 'true']),
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
                    TextEntry::make('name'),
                    TextEntry::make('amount')
                        ->money(fn ($record) => $record->currencyCode),
                    TextEntry::make('billingOn')->default('No billing Date'),
                    TextEntry::make('shop.name'),
                    IconEntry::make('isTest')->label('is Test')->boolean(),
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
            'index' => Pages\ListBillingEvents::route('/'),
            'create' => Pages\CreateBillingEvents::route('/create'),
            'view' => Pages\ViewBillingEvents::route('/{record}'),
            'edit' => Pages\EditBillingEvents::route('/{record}/edit'),
        ];
    }
}
