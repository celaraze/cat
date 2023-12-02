<?php

namespace App\Filament\Resources;

use App\Filament\Actions\DeviceAction;
use App\Filament\Imports\DeviceCategoryImporter;
use App\Filament\Resources\DeviceCategoryResource\Pages\Create;
use App\Filament\Resources\DeviceCategoryResource\Pages\Edit;
use App\Filament\Resources\DeviceCategoryResource\Pages\Index;
use App\Filament\Resources\DeviceCategoryResource\Pages\View;
use App\Http\Middleware\FilamentLockTab;
use App\Models\DeviceCategory;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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

    protected static string|array $routeMiddleware = FilamentLockTab::class;

    protected static ?string $modelLabel = '设备分类';

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
                    ->label('名称'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(DeviceCategoryImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('info')
                    ->label('导入')
                    ->visible(function () {
                        return auth()->user()->can('import_device_category');
                    }),
                ExportAction::make()
                    ->label('导出')
                    ->visible(function () {
                        return auth()->user()->can('export_device_category');
                    }),
                DeviceAction::createDeviceCategory(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('名称')
                    ->maxLength(255)
                    ->required(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'create' => Create::route('/create'),
            'edit' => Edit::route('/{record}/edit'),
            'view' => View::route('/{record}'),
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
