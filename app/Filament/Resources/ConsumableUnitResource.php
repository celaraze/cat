<?php

namespace App\Filament\Resources;

use App\Filament\Actions\ConsumableUnitAction;
use App\Filament\Forms\ConsumableUnitForm;
use App\Filament\Imports\ConsumableUnitImporter;
use App\Filament\Resources\ConsumableUnitResource\Pages;
use App\Filament\Resources\ConsumableUnitResource\Pages\Edit;
use App\Filament\Resources\ConsumableUnitResource\Pages\Index;
use App\Filament\Resources\ConsumableUnitResource\Pages\View;
use App\Models\ConsumableUnit;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class ConsumableUnitResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = ConsumableUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('cat/menu.consumable_unit');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
        ];
        $consumable_unit_service = $page->getWidgetData()['record']->service();
        $can_update_consumable_unit = auth()->user()->can('update_consumable::unit');
        if ($consumable_unit_service->isDeleted() || ! $can_update_consumable_unit) {
            unset($navigation_items[2]);
        }

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
            'import',
            'export',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ConsumableUnitForm::createOrEdit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/consumable_unit.name')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 删除
                ConsumableUnitAction::delete()
                    ->visible(function (ConsumableUnit $consumable_unit) {
                        $is_deleted = $consumable_unit->service()->isDeleted();

                        return ! $is_deleted && auth()->user()->can('delete_consumable::unit');
                    }),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(ConsumableUnitImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label(__('cat/action.import'))
                    ->visible(function () {
                        return auth()->user()->can('import_consumable::unit');
                    }),
                // 导出
                ExportAction::make()
                    ->label(__('cat/action.export'))
                    ->visible(function () {
                        return auth()->user()->can('export_consumable::unit');
                    }),
                // 创建
                ConsumableUnitAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_consumable::unit');
                    }),
                ConsumableUnitAction::toConsumable(),
            ])
            ->heading(__('cat/menu.consumable_unit'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Index::route('/'),
            'create' => Pages\Create::route('/create'),
            'view' => Pages\View::route('/{record}'),
            'edit' => Pages\Edit::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
