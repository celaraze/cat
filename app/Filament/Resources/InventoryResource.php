<?php

namespace App\Filament\Resources;

use App\Filament\Actions\InventoryAction;
use App\Filament\Resources\InventoryResource\Pages;
use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Models\Inventory;
use App\Utils\AssetUtil;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = '工作流';

    protected static ?string $modelLabel = '盘点';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名称'),
                Tables\Columns\TextColumn::make('class_name')
                    ->formatStateUsing(function (string $state) {
                        return AssetUtil::mapper($state);
                    })
                    ->label('资产'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('创建人'),
                Tables\Columns\TextColumn::make('hasTracks.check')
                    ->label('已盘点数量')
                    ->formatStateUsing(function (string $state) {
                        $checks = explode(',', $state);
                        $array_filter = array_filter($checks, function ($value) {
                            return $value != 0;
                        });
                        return count($array_filter);
                    }),
                Tables\Columns\TextColumn::make('hasTracks.asset_number')
                    ->label('资产总数')
                    ->formatStateUsing(function (string $state) {
                        $asset_numbers = explode(',', $state);
                        return count($asset_numbers);
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                InventoryAction::createInventory()
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Group::make()->schema([
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make()
                                ->schema([
                                    Group::make([
                                        TextEntry::make('name')
                                            ->label('任务名称'),
                                    ]),
                                    Group::make([
                                        TextEntry::make('class_name')
                                            ->formatStateUsing(function (string $state) {
                                                return AssetUtil::mapper($state);
                                            })
                                            ->label('资产'),
                                    ])
                                ])
                        ])
                    ])
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('创建人')
                    ])
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\HasTrackRelationManager::make()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Index::route('/'),
            'create' => Pages\Create::route('/create'),
            'view' => Pages\View::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
