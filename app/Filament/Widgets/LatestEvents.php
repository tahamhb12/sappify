<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Shop\OrderResource;
use App\Models\Shop\Order;
use App\Models\ShopifyAppEvent;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Squire\Models\Currency;
use Tables\Columns\TextColumn;

class LatestEvents extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(ShopifyAppEvent::query())
            ->defaultPaginationPageOption(5) 
            ->defaultSort('occurred_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make("id"),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make("app.name"),
                Tables\Columns\TextColumn::make("shop.name"),
                Tables\Columns\TextColumn::make("occurred_at")->date()->sortable(),
            ]);
    }
}
