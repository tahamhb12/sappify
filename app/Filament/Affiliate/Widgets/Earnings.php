<?php

namespace App\Filament\Affiliate\Widgets;

use App\Models\Earning;
use App\Models\Payout;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Earnings extends BaseWidget
{
    protected function getStats(): array
    {
        $earnings = number_format(Earning::where('user_id', auth()->id())->sum('earnings') ?? 0, 2);
        $next_payout_amount = Payout::where('user_id',auth()->user()->id)->where('status','approved')->sum("amount");
        return [
            Stat::make("Next Payout","$".$earnings - $next_payout_amount)
            ->chart([1, 3, 5, 10, 20, 40]),
            Stat::make("Total Earnings","$".$earnings)
            ->chart([1, 3, 5, 10, 20, 40]),
        ];
    }
}
