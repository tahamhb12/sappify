<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateProgramResource\Pages;
use App\Filament\Resources\AffiliateProgramResource\RelationManagers;
use App\Filament\Resources\AffiliateProgramResource\RelationManagers\UsersRelationManager;
use App\Models\AffiliateProgram;
use App\Models\ShopifyApp;
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

class AffiliateProgramResource extends Resource
{
    protected static ?string $model = AffiliateProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('app_id')
                ->relationship('app', 'name')
                ->label('App')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set){
                    $app_name=ShopifyApp::find($state)->name;
                    $slug_name = Str::slug($app_name);
                    return $set('app_url',$slug_name);
                }
                ),
            TextInput::make('app_url')
                ->label('App URL')
                ->prefix('https://apps.shopify.com/'),
            TextInput::make('amount_per_install')->numeric()->suffixIcon('heroicon-o-currency-dollar')->maxValue(10),
            TextInput::make('commission_rate')->numeric()->suffixIcon('heroicon-o-percent-badge')->maxValue(50),
            Select::make('min_payout')
            ->options(['20'=>'20',
                '40'=>'40',
                '60'=>'60',
                '80'=>'80',
                '100'=>'100'])
            ->suffixIcon('heroicon-o-currency-dollar'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('app.name'),
                TextColumn::make('app_url')->limit(24),
                TextColumn::make('amount_per_install')->money('USD')->label('Per install'),
                TextColumn::make('commission_rate')->label('Commission')->formatStateUsing(fn($state)=>$state.'%'),
                TextColumn::make('min_payout')->money('USD'),
                TextColumn::make('sign_up_page')->copyable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
/*                         TextEntry::make("commission_rate")->label(false)
                        ->formatStateUsing(fn ($state, $record) => "<button style='background-color:black;padding:10px;color:white;border-radius:12px;margin-top:-30px;font-weight:bold;'>Become an affiliate</button>")
                        ->html(), */
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
            UsersRelationManager::class
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
