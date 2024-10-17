<?php

namespace App\Filament\Pages;

use App\Models\ShopifyApp;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make("")->schema([
                Select::make("App")->options(ShopifyApp::all()->pluck("name","id")),
                DatePicker::make("StartDate"),
                DatePicker::make("EndDate")
            ])->columns(3)
        ]);
    }
}

