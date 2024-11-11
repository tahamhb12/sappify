<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Partner;
use App\Models\Team;
use App\Services\ApiServices;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
                TextInput::make('api_key')->required()->unique(),
            ]);
    }

    protected function handleRegistration(array $data): Partner
    {
        $data['user_id'] = auth()->user()->id;

        $partner = new Partner($data);
        $api = new ApiServices($partner);
        $response = $api->checkPartner();
        if($response->ok()){
            return Partner::create($data);
        }else{
            Notification::make()
                ->title('Partner not found.')
                ->danger()
                ->send();
            throw ValidationException::withMessages([
                'partner_id' => 'Partner doesnt exist.',
            ]);
        }


      //  $partner->users()->attach(auth()->user());

    }
}
