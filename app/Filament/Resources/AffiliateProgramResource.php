<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateProgramResource\Pages;
use App\Filament\Resources\AffiliateProgramResource\RelationManagers;
use App\Filament\Resources\AffiliateProgramResource\RelationManagers\PayoutsRelationManager;
use App\Filament\Resources\AffiliateProgramResource\RelationManagers\ReferralsRelationManager;
use App\Filament\Resources\AffiliateProgramResource\RelationManagers\UsersRelationManager;
use App\Models\AffiliateProgram;
use App\Models\ShopifyApp;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use SebastianBergmann\CodeCoverage\Util\Percentage;
use Webbingbrasil\FilamentCopyActions\Tables\Actions\CopyAction;


class AffiliateProgramResource extends Resource
{
    protected static ?string $model = AffiliateProgram::class;
    protected static ?string $label = 'Programs';
    protected static ?string $navigationGroup = 'Affiliates';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('app_id')
                ->relationship('app', 'name', function ($query) {
                    $used_app_ids = \App\Models\AffiliateProgram::pluck('app_id')->toArray();
                    $query->whereNotIn('id', $used_app_ids)
                    ->where('partner_id', Filament::getTenant()->id);
                    ;
                })
                ->label('App')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set){
                    $app_name=ShopifyApp::find($state)->name;
                    $slug_name = Str::slug($app_name);
                    return $set('app_url',"https://apps.shopify.com/$slug_name");
                }
                ),
            TextInput::make('app_url')
                ->label('App URL'),
            TextInput::make('amount_per_install')->numeric()->suffixIcon('heroicon-o-currency-dollar')->maxValue(100),
            TextInput::make('commission_rate')->numeric()->suffixIcon('heroicon-o-percent-badge')->maxValue(100),
            TextInput::make('min_payout')
            ->suffixIcon('heroicon-o-currency-dollar')->numeric()->maxLength(3)->maxValue(100),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('app.name'),
                TextColumn::make('amount_per_install')->money('USD')->label('Per install'),
                TextColumn::make('commission_rate')->label('Commission')->formatStateUsing(fn($state)=>$state.'%'),
                TextColumn::make('min_payout')->money('USD'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                CopyAction::make()
                ->label('Share sign up link')
                ->icon('heroicon-o-share')
                ->color('primary')
                ->copyable(fn($record)=>$record->sign_up_page)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {

        return $infolist
            ->schema([
                Group::make()->schema([
                    Section::make("Image")->schema([
                        ImageEntry::make("app.image")->label(false)->default('images/shop.png')->size(40),
                        TextEntry::make("app.name")->label(false)
                        ->formatStateUsing(function($record,$state){
                            return "<div style='position:relative;left:47px; bottom:55px;font-weight:bold;'>$state</div>
                            <div style='position:relative; bottom:40px;'>Sing up now to start earning $record->commission_rate% for life</div>";
                        })->html(),
                    ])->collapsible(),
                ])->columnSpan(3),
                Section::make()->schema([
                    TextEntry::make("commission_rate")->icon('heroicon-o-currency-dollar')->formatStateUsing(fn($record)=>"$record->commission_rate% life commission")->label("Program summary"),
                    TextEntry::make('amount_per_install')->icon('heroicon-o-cursor-arrow-rays')->formatStateUsing(fn ($state) => "$" . number_format($state, 2) . " per install")->label(false),
                    TextEntry::make('min_payout')->icon('heroicon-o-wallet')->formatStateUsing(fn ($state) => "$" . number_format($state, 2) . " minimum payout")->label(false),
                    TextEntry::make('approval')->icon("heroicon-o-check-badge")->default("No approval required")->label(false),
                ])->columnSpan(1)
            ])->columns(4);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
            ReferralsRelationManager::class,
            PayoutsRelationManager::class
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAffiliatePrograms::route('/'),
            'create' => Pages\CreateAffiliateProgram::route('/create'),
            'view' => Pages\ViewAffiliateProgram::route('/{record}'),
            'edit' => Pages\EditAffiliateProgram::route('/{record}/edit'),
        ];
    }
}
