<?php

namespace App\Filament\Affiliate\Resources\AffiliateProgramResource\Pages;

use App\Filament\Affiliate\Resources\AffiliateProgramResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewAffiliateProgram extends ViewRecord
{
    protected static string $resource = AffiliateProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Join')
            ->label(fn($record) => auth()->user()->affiliatePrograms()->where('affiliate_program_id', $record->id)->exists() ? 'Joined' : 'Join')
            ->action(fn($record) => auth()->user()->affiliatePrograms()->attach($record->id) && Notification::make()->title('Successfully Joined')->success()->send())
            ->requiresConfirmation()
            ->color(fn($record) => auth()->user()->affiliatePrograms()->where('affiliate_program_id', $record->id)->exists() ? 'gray' : 'success')
            ->hidden(fn($record) => auth()->user()->affiliatePrograms()->where('affiliate_program_id', $record->id)->exists()),
            Action::make('Joined')
            ->label('Joined')
            ->color('gray')
            ->disabled()
            ->hidden(fn($record) => !auth()->user()->affiliatePrograms()->where('affiliate_program_id', $record->id)->exists()),

        ];
    }
}
