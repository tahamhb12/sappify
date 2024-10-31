<?php

namespace App\Filament\Resources\ShopifyAppResource\RelationManagers;

use App\Models\ShopifyAppEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShopsRelationManager extends RelationManager
{
    protected static string $relationship = 'shops';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                ->default('images/shop.png')
                ->label("Avatar"),
                Tables\Columns\TextColumn::make('name')
                ->label('App')
                ->formatStateUsing(function ($state,$record) {
                    return "<div>
                                <div style=font-weight:bold>
                                $state
                                </div>
                                <div style=font-size:13px>$record->myshopifyDomain</div>
                            </div>";
                })
                ->html()
                ->searchable(),
                Tables\Columns\TagsColumn::make('tags')->default('No Tags Yet')->label('Tags'),
                Tables\Columns\TextColumn::make('notes')->default('No notes'),
                Tables\Columns\TextColumn::make('description')->default('No description')->limit(19),
                Tables\Columns\TextColumn::make('status')->default(function ($record) {
                    $type = ShopifyAppEvent::where('shop_id', $record->id)
                        ->where(function ($query) {
                            $query->where('type', 'RELATIONSHIP_INSTALLED')
                                ->orWhere('type', 'RELATIONSHIP_UNINSTALLED');
                        })
                        ->orderBy('occurred_at', 'desc')
                        ->first()
                        ?->type ?? 'N/A';
                    if ($type === 'RELATIONSHIP_INSTALLED') {
                        return 'Installed';
                    } elseif ($type === 'RELATIONSHIP_UNINSTALLED') {
                        return 'Uninstalled';
                    }
                    return 'N/A';
                })->badge()
                ->color(function (string $state){
                    if($state=='Uninstalled') return 'danger';
                    return 'success';
                }),
           ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
