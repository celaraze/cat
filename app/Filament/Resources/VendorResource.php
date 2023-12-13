<?php

namespace App\Filament\Resources;

use App\Filament\Actions\VendorAction;
use App\Filament\Forms\VendorForm;
use App\Filament\Imports\VendorImporter;
use App\Filament\Resources\VendorResource\Pages\Contact;
use App\Filament\Resources\VendorResource\Pages\Edit;
use App\Filament\Resources\VendorResource\Pages\Index;
use App\Filament\Resources\VendorResource\Pages\View;
use App\Models\Vendor;
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

class VendorResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $modelLabel = '厂商';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = '基础数据';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
            Contact::class,
        ];
        $can_update_vendor = auth()->user()->can('update_vendor');
        if (! $can_update_vendor) {
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
        return $form->schema(VendorForm::createOrEdit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->searchable()
                    ->label('名称'),
                Tables\Columns\TextColumn::make('address')
                    ->toggleable()
                    ->searchable()
                    ->label('地址'),
                Tables\Columns\TextColumn::make('public_phone_number')
                    ->toggleable()
                    ->searchable()
                    ->label('对公电话'),
            ])
            ->filters([

            ])
            ->actions([
                VendorAction::delete()
                    ->visible(function () {
                        return auth()->user()->can('delete_vendor');
                    }),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(VendorImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label('导入')
                    ->visible(function () {
                        return auth()->user()->can('import_vendor');
                    }),
                // 导出
                ExportAction::make()
                    ->label('导出')
                    ->visible(function () {
                        return auth()->user()->can('export_vendor');
                    }),
                // 创建
                VendorAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_vendor');
                    }),
            ])
            ->heading('厂商');
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'edit' => Edit::route('/{record}/edit'),
            'contacts' => Contact::route('/{record}/contacts'),
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
