<?php

namespace App\Filament\Affiliate\Resources;

use App\Filament\Affiliate\Resources\PayoutResource\Pages;
use App\Filament\Affiliate\Resources\PayoutResource\RelationManagers;
use App\Models\Earning;
use App\Models\Payout;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayoutResource extends Resource
{
    protected static ?string $model = Payout::class;
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $affiliated_apps = $user->affiliatePrograms->pluck('app.name','id');
        $earnings = number_format(Earning::where('user_id', auth()->id())->sum('earnings') ?? 0, 2);
        $next_payout_amount = Payout::where('user_id',auth()->user()->id)->where('status','approved')->sum("amount");

        return $form
            ->schema([
                TextInput::make("amount")->numeric()
                ->maxValue($earnings - $next_payout_amount),
                TextInput::make("paypal_email")->email(),
                Select::make("affiliate_program_id")->options($affiliated_apps)->required()->label("Affiliate Program"),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("affiliateProgram.app.name")->label("Program"),
                TextColumn::make("amount")->money('usd'),
                TextColumn::make("paypal_email"),
                TextColumn::make("comment")->default("no comment"),
                TextColumn::make("status")
                ->formatStateUsing(function($state){
                    if($state == "approved") return 'Approved';
                    if($state == "rejected") return 'Rejected';
                    if($state == "pending") return 'Pending';
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
            'index' => Pages\ListPayouts::route('/'),
            'create' => Pages\CreatePayout::route('/create'),
        ];
    }
}
