<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditPartnerProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Partner profile';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('partner_id')->readOnly(),
                TextInput::make('name')->required(),
                TextInput::make(name: 'api_key')->readOnly(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make('delete')
                ->requiresConfirmation()
                ->record($this->tenant)
                ->successRedirectUrl('/admin'),
        ];
    }
}
