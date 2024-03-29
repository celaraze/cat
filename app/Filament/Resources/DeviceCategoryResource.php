<?php

namespace App\Filament\Resources;

use App\Filament\Actions\DeviceCategoryAction;
use App\Filament\Forms\DeviceCategoryForm;
use App\Filament\Imports\DeviceCategoryImporter;
use App\Filament\Resources\DeviceCategoryResource\Pages\Device;
use App\Filament\Resources\DeviceCategoryResource\Pages\Edit;
use App\Filament\Resources\DeviceCategoryResource\Pages\Index;
use App\Filament\Resources\DeviceCategoryResource\Pages\View;
use App\Models\DeviceCategory;
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

class DeviceCategoryResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = DeviceCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('cat/menu.device_category');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
            Device::class,
        ];
        $can_update_device_category = auth()->user()->can('update_device::category');
        if (! $can_update_device_category) {
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device_category.name')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 删除
                DeviceCategoryAction::delete()
                    ->visible(function (DeviceCategory $device_category) {
                        $is_deleted = $device_category->service()->isDeleted();

                        return ! $is_deleted && auth()->user()->can('delete_device::category');
                    }),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(DeviceCategoryImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label(__('cat/action.import'))
                    ->visible(function () {
                        return auth()->user()->can('import_device::category');
                    }),
                // 导出
                ExportAction::make()
                    ->label(__('cat/action.export'))
                    ->visible(function () {
                        return auth()->user()->can('export_device::category');
                    }),
                // 创建
                DeviceCategoryAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_device::category');
                    }),
                DeviceCategoryAction::toDevice(),
            ])
            ->heading(__('cat/menu.device_category'));
    }

    public static function form(Form $form): Form
    {
        return $form->schema(DeviceCategoryForm::createOrEdit());
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'edit' => Edit::route('/{record}/edit'),
            'devices' => Device::route('/{record}/devices'),
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
