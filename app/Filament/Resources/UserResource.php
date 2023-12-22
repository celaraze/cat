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

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('cat.menu.security');
    }

    public static function getModelLabel(): string
    {
        return __('cat.menu.user');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return ! config('app.demo_mode');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
        ];
        /* @var User $user */
        $user = $page->getWidgetData()['record'];
        /* @var User $auth_user */
        $auth_user = auth()->user();
        $can_update_user = $auth_user->can('update_user');
        $is_deleted = $user->service()->isDeleted();
        // 先判断权限符合以及是否是已删除用户
        if (! $can_update_user || $is_deleted) {
            unset($navigation_items[2]);
        }

        // 再判断如果当前被编辑的用户是超级管理员，并且当前登录用户不是操作管理员，则不允许修改
        if (isset($navigation_items[2]) && $user->is_super_admin() && ! $auth_user->is_super_admin()) {
            unset($navigation_items[2]);
        }

        return $page->generateNavigationItems($navigation_items);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /* @var User $record */
        return [
            __('cat.user.email') => $record->getAttribute('email'),
        ];
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
            'force_delete',
            'import',
            'export',
            'reset_password',
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
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->toggleable()
                    ->circular()
                    ->defaultImageUrl(('/images/default.jpg'))
                    ->label(__('cat.user.avatar')),
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->searchable()
                    ->label(__('cat.user.name')),
                Tables\Columns\TextColumn::make('email')
                    ->toggleable()
                    ->searchable()
                    ->badge()
                    ->icon('heroicon-s-envelope')
                    ->label(__('cat.user.email')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 清除密码
                UserAction::resetPassword()
                    ->visible(function (User $user) {
                        // DEMO 模式不允许清除密码
                        $demo_mode = config('app.demo_mode');
                        if ($demo_mode) {
                            return false;
                        }
                        /* @var User $auth_user */
                        $auth_user = auth()->user();
                        if ($auth_user->is_super_admin()) {
                            return true;
                        } else {
                            $can = auth()->user()->can('reset_password_user');

                            // 有重置密码权限的用户不能互相重置，权限冲突
                            $is_conflict = $user->can('reset_password_user');

                            return $can && ! $is_conflict;
                        }
                    }),
                // 删除用户
                UserAction::delete()
                    ->visible(function (User $user) {
                        if ($user->service()->isDeleted()) {
                            return false;
                        }
                        /* @var User $auth_user */
                        $auth_user = auth()->user();
                        // 超级管理员不允许删除自己，只能由其它超级管理员删除
                        if ($auth_user->is_super_admin() && $user->getKey() != $auth_user->getKey()) {
                            return true;
                        } else {
                            $can = auth()->user()->can('reset_password_user');

                            // 有重置密码权限的用户不能互相重置，权限冲突
                            $is_conflict = $user->can('reset_password_user');

                            return $can && ! $is_conflict;
                        }
                    }),
                // 永久删除.
                UserAction::forceDelete()
                    ->visible(function (User $user) {
                        $can = auth()->user()->can('force_delete_user');

                        return $can && $user->service()->isDeleted();
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
                    ->label(__('cat.action.import'))
                    ->visible(auth()->user()->can('import_user')),
                // 导出
                ExportAction::make()
                    ->label(__('cat.action.export'))
                    ->visible(auth()->user()->can('export_user')),
                // 创建
                UserAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_user');
                    }),
            ])
            ->heading(__('cat.menu.user'));
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
