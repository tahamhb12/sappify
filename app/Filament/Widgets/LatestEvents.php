<?php

namespace App\Filament\Widgets;

use App\Models\ShopifyAppEvent;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestEvents extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    use InteractsWithPageFilters;

    public function table(Table $table): Table
    {
        $selectedApp = $this->filters['App'];
        $partner = Filament::getTenant();

        return $table
            ->query(ShopifyAppEvent::query()->when($selectedApp, fn ($query) => $query->where('app_id', $selectedApp))
                ->when($partner, fn ($query) => $query->where('partner_id', $partner->id))
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('occurred_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('type_label'),
                Tables\Columns\TextColumn::make('app.name'),
                Tables\Columns\TextColumn::make('shop.name'),
                Tables\Columns\TextColumn::make('occurred_at')->date()->sortable(),
            ]);
    }
}
