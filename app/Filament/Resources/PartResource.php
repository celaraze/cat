<?php

namespace App\Filament\Resources;

use App\Filament\Actions\PartAction;
use App\Filament\Forms\PartForm;
use App\Filament\Imports\PartImporter;
use App\Filament\Resources\PartResource\Pages\Edit;
use App\Filament\Resources\PartResource\Pages\HasPart;
use App\Filament\Resources\PartResource\Pages\Index;
use App\Filament\Resources\PartResource\Pages\View;
use App\Models\Part;
use App\Services\PartCategoryService;
use App\Services\PartService;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class PartResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Part::class;

    protected static ?string $navigationIcon = 'heroicon-m-cpu-chip';

    protected static ?string $modelLabel = '配件';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = '资产';

    protected static ?string $recordTitleAttribute = 'asset_number';

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Index::class,
            View::class,
            Edit::class,
            HasPart::class,
        ]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /* @var Part $record */
        return [
            '设备' => $record->devices()->value('asset_number'),
            '用户' => $record->devices()->first()?->users()->value('name'),
        ];
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
            'import',
            'export',
            'retire',
            'force_retire',
            'set_auto_asset_number_rule',
            'reset_auto_asset_number_rule',
            'set_retire_flow',
            'create_has_part',
            'delete_has_part',
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('照片')
                    ->circular()
                    ->toggleable()
                    ->defaultImageUrl(('/images/default.jpg')),
                Tables\Columns\TextColumn::make('asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->toggleable()
                    ->label('品牌'),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->toggleable()
                    ->label('分类'),
                Tables\Columns\TextColumn::make('specification')
                    ->searchable()
                    ->toggleable()
                    ->label('规格'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->multiple()
                    ->options(PartCategoryService::pluckOptions())
                    ->label('分类'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    // 流程报废
                    PartAction::retirePart()
                        ->visible(function () {
                            $can = auth()->user()->can('retire_part');

                            return $can && PartService::isSetRetireFlow();
                        }),
                    // 强制报废
                    PartAction::forceRetirePart()
                        ->visible(function () {
                            return auth()->user()->can('force_retire_part');
                        }),
                ]),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(PartImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label('导入')
                    ->visible(function () {
                        return auth()->user()->can('import_part');
                    }),
                // 导出
                ExportAction::make()
                    ->label('导出')
                    ->visible(function () {
                        return auth()->user()->can('export_part');
                    }),
                // 创建
                PartAction::createPart()->visible(function () {
                    return auth()->user()->can('create_part');
                }),
                Tables\Actions\ActionGroup::make([
                    // 前往配件分类
                    PartAction::toPartCategory(),
                    // 配置资产编号自动生成
                    PartAction::setAssetNumberRule()
                        ->visible(function () {
                            return auth()->user()->can('set_auto_asset_number_rule_part');
                        }),
                    // 重置资产编号配置流程
                    PartAction::resetAssetNumberRule()
                        ->visible(function () {
                            return auth()->user()->can('reset_auto_asset_number_rule_part');
                        }),
                    // 配置配件报废流程
                    PartAction::setPartRetireFlow()
                        ->visible(function () {
                            return auth()->user()->can('set_retire_flow_part');
                        }),
                ])
                    ->label('高级')
                    ->icon('heroicon-m-cog-8-tooth')
                    ->button(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema(PartForm::createOrEdit());
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
                                        TextEntry::make('asset_number')
                                            ->label('资产编号')
                                            ->badge()
                                            ->color('primary'),
                                        TextEntry::make('category.name')
                                            ->label('分类'),
                                    ]),
                                    Group::make([
                                        TextEntry::make('sn')
                                            ->label('序列号'),
                                        TextEntry::make('brand.name')
                                            ->label('品牌'),
                                        TextEntry::make('specification')
                                            ->label('规格'),
                                    ]),
                                ]),
                        ]),
                    ]),
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make()
                    ->schema([
                        ImageEntry::make('image')
                            ->disk('public')
                            ->label('照片')
                            ->defaultImageUrl(('/images/default.jpg')),
                    ]),
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'edit' => Edit::route('/{record}/edit'),
            'view' => View::route('/{record}'),
            'parts' => HasPart::route('{record}/has_parts'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
