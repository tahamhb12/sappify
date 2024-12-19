<?php

namespace App\Filament\Affiliate\Pages;

use App\Models\AffiliateProgram;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanUseDatabaseTransactions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Actions\ButtonAction;
use Filament\Pages\Concerns\HasRoutes;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Pages\SimplePage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterAffiliate extends SimplePage implements HasForms
{
    use CanUseDatabaseTransactions;
    use HasRoutes;
    use InteractsWithFormActions;

    public ?array $data = [];
    public $affiliate_program;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.affiliate.pages.register-affiliate';

    public function mount($unique_id)
    {
        $affiliate_program = AffiliateProgram::where('unique_id', $unique_id)
            ->firstOrFail();

        $this->affiliate_program =$affiliate_program;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->unique()->required()->email()->label("Email Adress"),
                TextInput::make('password')->required()->password()->revealable(),
                TextInput::make('password_confirmation')->required()->same("password")->password()->revealable(),
            ])->statePath('data');
    }

    public function Register(){
        $data = $this->form->getState();
        $user = User::create([
            'name' => $data["name"],
            'role' => 'affiliate',
            'email' => $data["email"],
            'password' => Hash::make($data["password"])
        ]);
        Auth::login($user);
        return redirect('/affiliate');
    }

    protected function getFormActions(){
        return [
            Action::make("Register")
            ->submit("Register")
            ->extraAttributes(['class' => 'w-full text-center']),
        ];
    }


}
