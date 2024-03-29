<?php

namespace App\Filament\Resources;

use App\Filament\Actions\BrandAction;
use App\Filament\Forms\BrandForm;
use App\Filament\Imports\BrandImporter;
use App\Filament\Resources\BrandResource\Pages\Edit;
use App\Filament\Resources\BrandResource\Pages\Index;
use App\Filament\Resources\BrandResource\Pages\View;
use App\Models\Brand;
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

class BrandResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-s-tag';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('cat/menu.basic_data');
    }

    public static function getModelLabel(): string
    {
        return __('cat/menu.brand');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation = [
            Index::class,
            View::class,
            Edit::class,
        ];
        $brand_service = $page->getWidgetData()['record']->service();
        $can_update_brand = auth()->user()->can('update_brand');
        if ($brand_service->isDeleted() || ! $can_update_brand) {
            unset($navigation[2]);
        }

        return $page->generateNavigationItems($navigation);
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
        return $form->schema(BrandForm::createOrEdit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('cat/brand.name')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 删除
                BrandAction::delete()
                    ->visible(function (Brand $brand) {
                        $is_deleted = $brand->service()->isDeleted();
                        $can = auth()->user()->can('delete_brand');

                        return ! $is_deleted && $can;
                    }),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(BrandImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label(__('cat/action.import'))
                    ->visible(function () {
                        return auth()->user()->can('import_brand');
                    }),
                // 导出
                ExportAction::make()
                    ->label(__('cat/action.export'))
                    ->visible(function () {
                        return auth()->user()->can('export_brand');
                    }),
                // 创建
                BrandAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_brand');
                    }),
            ])
            ->heading(__('cat/menu.brand'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'edit' => Edit::route('/{record}/edit'),
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
