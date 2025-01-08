<?php

namespace App\Filament\Resources\AffiliateProgramResource\RelationManagers;

use App\Models\BillingEvents;
use App\Models\Earning;
use App\Models\Shop;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReferralsRelationManager extends RelationManager
{
    protected static string $relationship = 'referrals';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("status")->options([1=>"Approved",0=>"Rejected"])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('note')
            ->columns([
                TextColumn::make("customer_shop")->copyable(),
                TextColumn::make("date")->date(),
                TextColumn::make("note")->default("no notes"),
                TextColumn::make("user.name")->searchable(),
                TextColumn::make("status")
                    ->formatStateUsing(function ($state) {
                        if ($state == "approved") return 'Approved';
                        if ($state == "rejected") return 'Rejected';
                        if ($state == "pending") return 'Pending';
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        "approved" => 'success',
                        "rejected" => 'danger',
                    })
            ])
            ->filters([
                SelectFilter::make("status")->options([
                    "pending" => "pending",
                    "approved" => "approved",
                    "rejected" => "rejected",
                ])->multiple()
            ])
            ->actions([
                Action::make("Accept")
                ->color('success')
                ->visible(fn($record) => $record->status === 'pending')
                ->action(function ($record) {
                    $amount_per_install = (int) $record->affiliateProgram->amount_per_install;
                    Earning::create([
                        'user_id' => $record->user_id,
                        'earnings' => $amount_per_install,
                        'type' => 'install',
                    ]);
                    $record->update(['status' => 'approved']);
                }),
                Action::make("Reject")
                ->color("danger")
                ->visible(fn($record) => $record->status === 'pending')
                ->action(function ($record) {
                    $record->update(['status' => 'rejected']);
                }),


            ])
            ->bulkActions([
            ]);
    }
}
