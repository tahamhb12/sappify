<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralResource\Pages;
use App\Filament\Resources\ReferralResource\RelationManagers;
use App\Models\Referral;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function PHPUnit\Framework\returnSelf;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;
    protected static ?string $navigationGroup = 'Affiliates';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("is_approved")->options([1=>"Approved",0=>"Rejected"])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("app.name")->searchable(),
                TextColumn::make("customer_shop")->formatStateUsing(fn($state)=> "https://".$state.".myshopify.com")->copyable(),
                TextColumn::make("date")->date(),
                TextColumn::make("note")->default("no notes"),
                TextColumn::make("user.name")->searchable(),
                TextColumn::make('is_approved')->label('Approved')
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
                ->options([1=>"Approved",0=>'Rejected','pending'])
                ->label('Status')
                ->multiple(),

            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferrals::route('/'),
            // 'create' => Pages\CreateReferral::route('/create'),
            // 'view' => Pages\ViewReferral::route('/{record}'),
            'edit' => Pages\EditReferral::route('/{record}/edit'),
        ];
    }
}
