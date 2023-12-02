<?php

namespace App\Filament\Resources;

use App\Filament\Actions\SoftwareAction;
use App\Filament\Imports\SoftwareCategoryImporter;
use App\Filament\Resources\SoftwareCategoryResource\Pages\Create;
use App\Filament\Resources\SoftwareCategoryResource\Pages\Edit;
use App\Filament\Resources\SoftwareCategoryResource\Pages\Index;
use App\Filament\Resources\SoftwareCategoryResource\Pages\View;
use App\Http\Middleware\FilamentLockTab;
use App\Models\SoftwareCategory;
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

class SoftwareCategoryResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = SoftwareCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = '软件分类';

    protected static string|array $routeMiddleware = FilamentLockTab::class;

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
            ->schema([
                TextInput::make('name')
                    ->label('名称')
                    ->maxLength(255)
                    ->required(),
            ]);
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
            ->headerActions([
                ImportAction::make()
                    ->importer(SoftwareCategoryImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('info')
                    ->label('导入')
                    ->visible(function () {
                        return auth()->user()->can('import_software_category');
                    }),
                ExportAction::make()
                    ->label('导出')
                    ->visible(function () {
                        return auth()->user()->can('export_software_category');
                    }),
                SoftwareAction::createSoftwareCategory(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
