<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Partner;
use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterPartner extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register Partner';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('partner_id')->required()->unique(),
                TextInput::make('name')->required()->unique(),
                TextInput::make(name: 'api_key')->required()->unique(),
            ]);
    }

    protected function handleRegistration(array $data): Partner
    {
        $data['user_id'] = auth()->user()->id;

        $partner = Partner::create($data);


      //  $partner->users()->attach(auth()->user());

        return $partner;
    }
}
