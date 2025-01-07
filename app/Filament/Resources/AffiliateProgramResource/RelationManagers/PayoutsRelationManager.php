<?php

namespace App\Filament\Resources\AffiliateProgramResource\RelationManagers;

use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayoutsRelationManager extends RelationManager
{
    protected static string $relationship = 'payouts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('comment')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                TextColumn::make("amount")->money('usd'),
                TextColumn::make("user.name")->searchable(),
                TextColumn::make("paypal_email"),
                TextColumn::make("comment")->default("no comment"),
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
                ])
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make("Accept")
                    ->color('success')
                    ->visible(fn($record) => $record->status === "pending")
                    ->action(function ($record, array $data) {
                        $record->update([
                            "status" => "approved",
                            "comment" => $data['comment'],
                        ]);
                    })
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Comment')
                            ->required()
                            ->placeholder('Add a comment...')
                    ])
                    ->modalHeading('Accept Payout')
                    ->modalSubmitActionLabel('Approve Payout'),

                Action::make("Reject")
                    ->color('danger')
                    ->visible(fn($record) => $record->status === "pending")
                    ->action(function ($record, array $data) {
                        $record->update([
                            "status" => "rejected",
                            "comment" => $data['comment'],
                        ]);
                    })
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Comment')
                            ->required()
                            ->placeholder('Add a comment...')
                    ])
                    ->modalHeading('Reject Payout')
                    ->modalSubmitActionLabel('Reject Payout'),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
