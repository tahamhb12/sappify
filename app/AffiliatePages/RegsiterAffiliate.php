<?php

namespace App\AffiliatePages;

use App\Filament\Resources\AffiliateProgramResource;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasRoutes;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Resources\Pages\Page;

class RegsiterAffiliate extends SimplePage
{
    use CanUseDatabaseTransactions;
    use HasRoutes;
    use InteractsWithFormActions;
    protected static string $view = 'affiliate.register-affiliate';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->required()->unique()->email()->label("Email Adress"),
                TextInput::make('password')->required()->password(),
                TextInput::make('password_confirmation')->required()->password(),
            ]);
    }

    protected function handleRegistration(array $data)
    {
        $data['role'] = 'affiliate';
        User::create($data);
    }


}
