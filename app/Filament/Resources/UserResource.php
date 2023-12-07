<?php

namespace App\Filament\Resources;

use App\Filament\Actions\UserAction;
use App\Filament\Forms\UserForm;
use App\Filament\Imports\UserImporter;
use App\Filament\Resources\UserResource\Pages\Edit;
use App\Filament\Resources\UserResource\Pages\Index;
use App\Filament\Resources\UserResource\Pages\View;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-circle';

    protected static ?string $modelLabel = '用户';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = '安全';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Index::class,
            View::class,
            Edit::class,
        ]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /* @var $record User */
        return [
            '账户' => $record->getAttribute('email'),
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
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(UserForm::edit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名称'),
                Tables\Columns\TextColumn::make('email')
                    ->label('邮箱'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 详情
                Tables\Actions\ViewAction::make()
                    ->visible(function () {
                        return auth()->user()->can('view_user');
                    }),
                // 编辑
                Tables\Actions\EditAction::make()
                    ->visible(function () {
                        return auth()->user()->can('update_user');
                    }),
                // 清除密码
                UserAction::resetPassword()
                    ->visible(function () {
                        $can = auth()->user()->can('reset_password_user');
                        // DEMO 模式不允许清除密码
                        $demo_mode = config('app.demo_mode');
                        return $can && !$demo_mode;
                    }),
                // 删除用户
                UserAction::deleteUser()
                    ->visible(function () {
                        return auth()->user()->can('delete_user');
                    }),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(UserImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label('导入')
                    ->visible(auth()->user()->can('import_user')),
                // 导出
                ExportAction::make()
                    ->label('导出')
                    ->visible(auth()->user()->can('export_user')),
                // 创建
                UserAction::createUser()
                    ->visible(function () {
                        return auth()->user()->can('create_user');
                    }),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'edit' => Edit::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return true;
    }
}
