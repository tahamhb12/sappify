<?php

namespace App\Filament\Affiliate\Pages;

use App\Filament\Affiliate\Widgets\AffiliatedApps;
use App\Filament\Affiliate\Widgets\Earnings;
use App\Filament\Affiliate\Widgets\Payouts;
use App\Filament\Affiliate\Widgets\StatsOverview;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;
    public function getWidgets(): array
    {
        return [
            Earnings::class,
            AffiliatedApps::class
        ];
    }


    public function filtersForm(Form $form): Form
    {
        return $form->schema([
        ]);
    }
}
