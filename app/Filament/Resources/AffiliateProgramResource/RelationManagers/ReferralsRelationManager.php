<?php

namespace App\Filament\Resources\AffiliateProgramResource\RelationManagers;

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
                Select::make("is_approved")->options([1=>"Approved",0=>"Rejected"])
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
                TextColumn::make('is_approved')->label('Status')
                ->default('pending')
                ->formatStateUsing(function($state){
                    if($state == 1) return 'Approved';
                    if($state == 0) return 'Rejected';
                    if($state == "pending") return 'Pending';
                })
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'pending' => 'warning',
                    1 => 'success',
                    0 => 'danger',
                })
            ])
            ->filters([
                SelectFilter::make('is_approved')
                ->options([1=>"Approved",0=>'Rejected','null'=>"null"])
                ->label('Status')
                ->multiple(),

            ])
            ->actions([
                Action::make("Accept")
                ->color('success')
                ->visible(fn($record) => $record->is_approved === null)
                ->action(function ($record) {
                    $record->update(['is_approved' => true]);
                }),
                Action::make("Reject")
                ->color("danger")
                ->visible(fn($record) => $record->is_approved === null)
                ->action(function ($record) {
                    $record->update(['is_approved' => false]);
                }),


            ])
            ->bulkActions([
            ]);
    }
}
