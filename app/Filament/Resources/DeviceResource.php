<?php

namespace App\Filament\Resources;

use App\Enums\AssetEnum;
use App\Filament\Actions\DeviceAction;
use App\Filament\Actions\DeviceHasUserAction;
use App\Filament\Actions\TicketAction;
use App\Filament\Forms\DeviceForm;
use App\Filament\Imports\DeviceImporter;
use App\Filament\Resources\DeviceResource\Pages\Edit;
use App\Filament\Resources\DeviceResource\Pages\HasPart;
use App\Filament\Resources\DeviceResource\Pages\HasSecret;
use App\Filament\Resources\DeviceResource\Pages\HasSoftware;
use App\Filament\Resources\DeviceResource\Pages\HasUser;
use App\Filament\Resources\DeviceResource\Pages\Index;
use App\Filament\Resources\DeviceResource\Pages\Ticket;
use App\Filament\Resources\DeviceResource\Pages\View;
use App\Models\Device;
use App\Services\BrandService;
use App\Services\DeviceCategoryService;
use App\Services\DeviceService;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Exception;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class DeviceResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-s-server';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'asset_number';

    public static function getNavigationGroup(): ?string
    {
        return __('cat/menu.asset');
    }

    public static function getModelLabel(): string
    {
        return __('cat/menu.device');
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /* @var Device $record */
        return [
            __('cat/name') => $record->getAttribute('name'),
            __('cat/user') => $record->users()->value('name') ?? __('cat/none'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
            HasUser::class,
            HasPart::class,
            HasSoftware::class,
            Ticket::class,
            HasSecret::class,
        ];
        $device_service = $page->getWidgetData()['record']->service();
        $can_update_device = auth()->user()->can('update_device');
        if ($device_service->isRetired() || ! $can_update_device) {
            unset($navigation_items[2]);
        }

        return $page->generateNavigationItems($navigation_items);
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return self::getUrl('view', ['record' => $record]);
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
            'assign_user',
            'delete_assign_user',
            'import',
            'export',
            'retire',
            'force_retire',
            'set_auto_asset_number_rule',
            'reset_auto_asset_number_rule',
            'set_retire_flow',
            'create_has_part',
            'delete_has_part',
            'create_has_software',
            'delete_has_software',
            'create_has_secret',
            'delete_has_secret',
            'batch_delete_has_part',
            'batch_delete_has_software',
            'batch_delete_has_secret',
            'view_token',
        ];
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->toggleable()
                    ->circular()
                    ->defaultImageUrl(('/images/default.jpg'))
                    ->label(__('cat/device.image')),
                Tables\Columns\TextColumn::make('asset_number')
                    ->searchable()
                    ->toggleable()
                    ->copyable()
                    ->badge()
                    ->sortable()
                    ->color('gray')
                    ->label(__('cat/device.asset_number')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label(__('cat/device.name')),
                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label(__('cat/device.brand')),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label(__('cat/device.category')),
                Tables\Columns\TextColumn::make('users.name')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->label(__('cat/device.user')),
                Tables\Columns\TextColumn::make('sn')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label(__('cat/device.sn')),
                Tables\Columns\TextColumn::make('specification')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label(__('cat/device.specification')),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return AssetEnum::statusText($state);
                    })
                    ->color(function ($state) {
                        return AssetEnum::statusColor($state);
                    })
                    ->label(__('cat/device.status')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->multiple()
                    ->options(DeviceCategoryService::pluckOptions())
                    ->label(__('cat/device.category_id')),
                Tables\Filters\SelectFilter::make('brand_id')
                    ->multiple()
                    ->options(BrandService::pluckOptions())
                    ->label(__('cat/device.brand_id')),
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options(AssetEnum::allStatusText())
                    ->label(__('cat/device.status')),
            ])
            ->actions([
                DeviceAction::summary(),
                // 分配用户
                DeviceHasUserAction::create()
                    ->visible(function (Device $device) {
                        $can = auth()->user()->can('assign_user_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired && ! $device->hasUsers()->count();
                    }),
                Tables\Actions\ActionGroup::make([
                    // 创建工单
                    TicketAction::createFromDevice(),
                    // 解除用户
                    DeviceHasUserAction::delete()
                        ->visible(function (Device $device) {
                            $can = auth()->user()->can('delete_assign_user_device');

                            return $can && $device->hasUsers()->count();
                        }),
                    // 流程报废
                    DeviceAction::retire()
                        ->visible(function () {
                            $can = auth()->user()->can('retire_device');

                            return $can && DeviceService::isSetRetireFlow();
                        }),
                    // 强制报废
                    DeviceAction::forceRetire()
                        ->visible(function () {
                            return auth()->user()->can('force_retire_device');
                        }),
                ])
                    ->visible(function (Device $device) {
                        return ! $device->service()->isRetired();
                    }),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(DeviceImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label(__('cat/device.action.import'))
                    ->visible(auth()->user()->can('import_device')),
                // 导出
                ExportAction::make()
                    ->label(__('cat/device.action.export'))
                    ->visible(auth()->user()->can('export_device')),
                // 创建
                DeviceAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_device');
                    }),
                Tables\Actions\ActionGroup::make([
                    // 前往分类
                    DeviceAction::toCategory(),
                    // 配置资产编号自动生成规则
                    DeviceAction::setAssetNumberRule()
                        ->visible(function () {
                            return auth()->user()->can('set_auto_asset_number_rule_device');
                        }),
                    // 重置资产编号自动生成规则
                    DeviceAction::resetAssetNumberRule()
                        ->visible(function () {
                            return auth()->user()->can('reset_auto_asset_number_rule_device');
                        }),
                    // 配置设备报废流程
                    DeviceAction::setRetireFlow()
                        ->visible(function () {
                            return auth()->user()->can('set_retire_flow_device');
                        }),
                ])
                    ->label(__('cat/device.action.advance'))
                    ->icon('heroicon-m-cog-8-tooth')
                    ->button(),
            ])
            ->heading(__('cat/menu.device'));
    }

    public static function form(Form $form): Form
    {
        return $form->schema(DeviceForm::createOrEdit());
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
                                            ->label(__('cat/device.asset_number'))
                                            ->badge()
                                            ->color('primary'),
                                        TextEntry::make('name')
                                            ->label(__('cat/device.name')),
                                        TextEntry::make('category.name')
                                            ->label(__('cat/device.category')),
                                    ]),
                                    Group::make([
                                        TextEntry::make('sn')
                                            ->label(__('cat/device.sn')),
                                        TextEntry::make('brand.name')
                                            ->label(__('cat/device.brand')),
                                        TextEntry::make('specification')
                                            ->label(__('cat/device.specification')),
                                    ]),
                                ]),
                        ]),
                    ]),
                Section::make()->schema([
                    TextEntry::make('description')
                        ->label(__('cat/device.description')),
                ]),
                Section::make()->schema([
                    RepeatableEntry::make('additional')
                        ->schema([
                            TextEntry::make('name')
                                ->columnSpan(1)
                                ->hiddenLabel(),
                            TextEntry::make('text')
                                ->columnSpan(1)
                                ->hiddenLabel(),
                        ])
                        ->grid()
                        ->columns()
                        ->label(__('cat/device.additional')),
                ]),
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make()
                    ->schema([
                        ImageEntry::make('image')
                            ->disk('public')
                            ->height(300)
                            ->defaultImageUrl(('/images/default.jpg'))
                            ->label(__('cat/device.image')),
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
            'users' => HasUser::route('/{record}/has_users'),
            'parts' => HasPart::route('/{record}/has_parts'),
            'software' => HasSoftware::route('{record}/has_software'),
            'tickets' => Ticket::route('/{record}/has_tickets'),
            'secrets' => HasSecret::route('/{record}/has_secrets'),
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

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
