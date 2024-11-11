<?php

namespace App\Filament\Pages;

use App\Models\ShopifyApp;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        $partner = Filament::getTenant();

        return $form->schema([
            Section::make('')->schema([
                Select::make('App')->options(ShopifyApp::where('partner_id', $partner->id)->pluck('name', 'id')),
                DatePicker::make('StartDate'),
                DatePicker::make('EndDate'),
            ])->columns(3),
        ]);
    }
}
