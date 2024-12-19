<?php

namespace App\Filament\Resources\AffiliateProgramResource\Pages;

use App\Filament\Resources\AffiliateProgramResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewAffiliateProgram extends ViewRecord
{
    protected static string $resource = AffiliateProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Action::make('Share sign up link')
            ->icon('heroicon-s-share')
            ->action(function ($livewire) {
                $livewire->js(
                    'window.navigator.clipboard.writeText("'. $this->record->sign_up_page .'");
                    $tooltip("'.__('Copied to clipboard').'", { timeout: 1500 });');
            })
        ];
    }
}
