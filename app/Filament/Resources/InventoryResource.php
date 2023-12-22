<?php

namespace App\Filament\Resources;

use App\Enums\AssetEnum;
use App\Filament\Actions\InventoryAction;
use App\Filament\Resources\InventoryResource\Pages\HasTrack;
use App\Filament\Resources\InventoryResource\Pages\Index;
use App\Filament\Resources\InventoryResource\Pages\View;
use App\Models\Inventory;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('cat/menu.workflow');
    }

    public static function getModelLabel(): string
    {
        return __('cat/menu.inventory');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            HasTrack::class,
        ];

        return $page->generateNavigationItems($navigation_items);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'check',
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('cat/name')),
                Tables\Columns\TextColumn::make('class_name')
                    ->formatStateUsing(function (string $state) {
                        return AssetEnum::assetTypeText($state);
                    })
                    ->label(__('cat/class_name')),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('cat/creator')),
                Tables\Columns\TextColumn::make('hasTracks.check')
                    ->label(__('cat/resource.inventory.check'))
                    ->formatStateUsing(function (string $state) {
                        $checks = explode(',', $state);
                        $array_filter = array_filter($checks, function ($value) {
                            return $value != 0;
                        });

                        return count($array_filter);
                    }),
                Tables\Columns\TextColumn::make('hasTracks.asset_number')
                    ->label(__('cat/resource.inventory.asset_number'))
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

            ])
            ->headerActions([
                // 创建
                InventoryAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_inventory');
                    }),
            ])
            ->heading(__('cat/resource.inventory'));
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
                                                return AssetEnum::assetTypeText($state);
                                            })
                                            ->label(__('cat/inventory.class_name')),
                                    ]),
                                ]),
                        ]),
                    ]),
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('creator.name')
                            ->label(__('cat/inventory.creator')),
                    ]),
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'tracks' => HasTrack::route('/{record}/tracks'),
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

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
