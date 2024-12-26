<?php

namespace App\Filament\Affiliate\Pages;
use App\Models\AffiliateProgram;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasRoutes;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Resources\Pages\Page;

class AffiliateRequest extends SimplePage
{
    use CanUseDatabaseTransactions;
    use HasRoutes;
    use InteractsWithFormActions;

    public $affiliate_program;
    public $unique_id;

    protected static string $view = 'filament.affiliate.pages.affiliate-request';

    public function mount($app_id, $unique_id)
    {
        $affiliate_program = AffiliateProgram::where('app_id', $app_id)
            ->where('unique_id', $unique_id)
            ->firstOrFail();

        $this->affiliate_program = $affiliate_program;
        $this->unique_id = $unique_id;
    }

    public function redirectToAffiliateRegister()
    {
        return redirect("/affiliate/register");
    }


}
