<?php

namespace App\Filament\Affiliate\Resources;

use App\Filament\Affiliate\Resources\ReferralResource\Pages;
use App\Filament\Affiliate\Resources\ReferralResource\RelationManagers;
use App\Models\Referral;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $affiliatedApps = $user->affiliatePrograms()->with('app')->get();
        $appNames = $affiliatedApps->pluck('app.name','id');
        return $form
            ->schema([
                Select::make("app_id")->options($appNames)->required(),
                TextInput::make("customer_shop")->suffix(".myshopify.com")->required(),
                DatePicker::make("date")->required(),
                TextInput::make("note"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(Referral::query()->where('is_approved', true)->where('user_id', Auth::id()))
        ->columns([
                TextColumn::make("app.name"),
                TextColumn::make("customer_shop"),
                TextColumn::make("date"),
                TextColumn::make("note")->default("no notes"),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
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
            'create' => Pages\CreateReferral::route('/create'),
            // 'view' => Pages\ViewReferral::route('/{record}'),
            // 'edit' => Pages\EditReferral::route('/{record}/edit'),
        ];
    }
}
