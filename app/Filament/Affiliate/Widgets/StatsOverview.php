<?php

namespace App\Filament\Affiliate\Widgets;

use App\Models\Earning;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $earnings = number_format(Earning::where('user_id', auth()->id())->sum('earnings') ?? 0, 2);

        return [
            Stat::make("Earnings","$".$earnings)
            ->chart([1, 3, 5, 10, 20, 40]),

        ];
    }
}
