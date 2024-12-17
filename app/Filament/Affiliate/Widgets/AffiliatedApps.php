<?php

namespace App\Filament\Affiliate\Widgets;

use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Webbingbrasil\FilamentCopyActions\Tables\Actions\CopyAction;


class AffiliatedApps extends BaseWidget
{


    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
        ->query(function () {
                $user = auth()->user();
                return $user->affiliatePrograms()->getQuery();
            })
            ->columns([
                TextColumn::make('app.name'),
                TextColumn::make('commission_rate')->formatStateUsing(fn ($state) => $state.'%'),
                TextColumn::make('amount_per_install')->money('USD'),
                TextColumn::make('min_payout')->money('USD'),
                ])
                ->actions([
                    CopyAction::make()
                    ->label('Share affiliate link')
                    ->icon('heroicon-o-share')
                    ->color('primary')
                    ->copyable(fn($record)=>$record->app_url."?mref=".auth()->user()->referral_code)
                    ])
                    ->bulkActions([]);
                }
            }
