<?php

namespace App\Filament\Resources;

use App\Enums\AssetEnum;
use App\Filament\Actions\ConsumableAction;
use App\Filament\Forms\ConsumableForm;
use App\Filament\Imports\ConsumableImporter;
use App\Filament\Resources\ConsumableResource\Pages\Edit;
use App\Filament\Resources\ConsumableResource\Pages\Index;
use App\Filament\Resources\ConsumableResource\Pages\Track;
use App\Filament\Resources\ConsumableResource\Pages\View;
use App\Models\Consumable;
use App\Models\Device;
use App\Services\ConsumableService;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class ConsumableResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Consumable::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('cat/menu.asset');
    }

    public static function getModelLabel(): string
    {
        return __('cat/menu.consumable');
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /* @var Device $record */
        return [
            __('cat/consumable.name') => $record->getAttribute('name'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
            Track::class,
            ConsumableResource\Pages\Form::class,
        ];
        $consumable_service = $page->getWidgetData()['record']->service();
        $can_update_consumable = auth()->user()->can('update_consumable');
        if ($consumable_service->isRetired() || ! $can_update_consumable) {
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
            'import',
            'export',
            'retire',
            'force_retire',
            'set_retire_flow',
            'process_flow_has_form',
            'create_has_track',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ConsumableForm::createOrEdit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->toggleable()
                    ->circular()
                    ->defaultImageUrl(('/images/default.jpg'))
                    ->label(__('cat/consumable.image')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/consumable.name')),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/consumable.category')),
                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/consumable.brand')),
                Tables\Columns\TextColumn::make('unit.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/consumable.unit')),
                Tables\Columns\TextColumn::make('specification')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/consumable.specification')),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->formatStateUsing(fn ($state) => AssetEnum::statusText($state))
                    ->color(fn ($state) => AssetEnum::statusColor($state))
                    ->label(__('cat/consumable.status')),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->color(function ($state) {
                        return $state > 0 ? 'primary' : 'danger';
                    })
                    ->label(__('cat/consumable.quantity')),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    // 流程报废
                    ConsumableAction::retire()
                        ->visible(function (Consumable $consumable) {
                            $can = auth()->user()->can('retire_consumable');
                            $is_retiring = $consumable->service()->isRetiring();

                            return $can && ! $is_retiring && ConsumableService::isSetRetireFlow();
                        }),
                    // 强制报废
                    ConsumableAction::forceRetire()
                        ->visible(function (Consumable $consumable) {
                            $can = auth()->user()->can('force_retire_consumable');
                            $is_retiring = $consumable->service()->isRetiring();

                            return $can && ! $is_retiring;
                        }),
                ])
                    ->visible(function (Consumable $consumable) {
                        return ! $consumable->service()->isRetired();
                    }),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(ConsumableImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label(__('cat/action.import'))
                    ->visible(auth()->user()->can('import_consumable')),
                // 导出
                ExportAction::make()
                    ->label(__('cat/action.export'))
                    ->visible(auth()->user()->can('export_consumable')),
                // 创建
                ConsumableAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_consumable');
                    }),
                Tables\Actions\ActionGroup::make([
                    // 前往分类
                    ConsumableAction::toCategory(),
                    ConsumableAction::toUnit(),
                    // 配置设备报废流程
                    ConsumableAction::setRetireFlow()
                        ->visible(function () {
                            return auth()->user()->can('set_retire_flow_consumable');
                        }),
                ])
                    ->label(__('cat/action.advance'))
                    ->icon('heroicon-m-cog-8-tooth')
                    ->button(),
            ])
            ->heading(__('cat/menu.consumable'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'edit' => Edit::route('/{record}/edit'),
            'tracks' => Track::route('/{record}/tracks'),
            'forms' => ConsumableResource\Pages\Form::route('/{record}/forms'),
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
