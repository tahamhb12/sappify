<?php

namespace App\Filament\Affiliate\Resources;

use App\Filament\Affiliate\Resources\AffiliateProgramResource\Pages;
use App\Models\AffiliateProgram;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AffiliateProgramResource extends Resource
{
    protected static ?string $model = AffiliateProgram::class;

    protected static ?string $pluralLabel = 'Marketplace';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        return $table
            ->columns([
                TextColumn::make('app.name'),
                TextColumn::make('commission_rate')->formatStateUsing(fn ($state) => $state.'%'),
                TextColumn::make('amount_per_install')->money('USD'),
                TextColumn::make('min_payout')->money('USD'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            ])
                        ->bulkActions([
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {

        return $infolist
            ->schema([
                Group::make()->schema([
                    Section::make('Image')->schema([
                        ImageEntry::make('app.image')->label(false)->default('images/shop.png')->size(40),
                        TextEntry::make('app.name')->label(false)
                            ->formatStateUsing(function ($record, $state) {
                                return "<div style='position:relative;left:47px; bottom:55px;font-weight:bold;'>$state</div>
                            <div style='position:relative; bottom:40px;'>Sing up now to start earning $record->commission_rate% for life</div>";
                            })->html(),
                        /*                         TextEntry::make("commission_rate")->label(false)
                        ->formatStateUsing(fn ($state, $record) => "<button style='background-color:black;padding:10px;color:white;border-radius:12px;margin-top:-30px;font-weight:bold;'>Become an affiliate</button>")
                        ->html(), */
                    ])->collapsible(),
                ])->columnSpan(3),
                Section::make()->schema([
                    TextEntry::make('commission_rate')->icon('heroicon-o-currency-dollar')->formatStateUsing(fn ($record) => "$record->commission_rate% life commission")->label('Program summary'),
                    TextEntry::make('amount_per_install')->icon('heroicon-o-cursor-arrow-rays')->formatStateUsing(fn ($state) => '$'.number_format($state, 2).' per install')->label(false),
                    TextEntry::make('min_payout')->icon('heroicon-o-wallet')->formatStateUsing(fn ($state) => '$'.number_format($state, 2).' minimum payout')->label(false),
                    TextEntry::make('approval')->icon('heroicon-o-check-badge')->default('No approval required')->label(false),
                ])->columnSpan(1),
            ])->columns(4);
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
            'index' => Pages\ListAffiliatePrograms::route('/'),
            'view' => Pages\ViewAffiliateProgram::route('{record}'),
        ];
    }
}
