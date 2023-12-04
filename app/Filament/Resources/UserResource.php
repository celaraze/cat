<?php

namespace App\Filament\Resources;

use App\Filament\Actions\UserAction;
use App\Filament\Forms\UserForm;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-m-users';

    protected static ?string $modelLabel = '用户';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = '基础数据';

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
            'reset_password',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(UserForm::createOrEdit());
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
                //
            ])
            ->actions([
                // 清除密码
                UserAction::resetPassword()
                    ->visible(function () {
                        return auth()->user()->can('reset_password_user');
                    }),
                // 编辑
                Tables\Actions\EditAction::make()
                    ->visible(function () {
                        return auth()->user()->can('update_user');
                    }),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                // 创建
                Tables\Actions\CreateAction::make('创建用户')
                    ->slideOver()
                    ->icon('heroicon-m-plus')
                    ->visible(function () {
                        return auth()->user()->can('create_user');
                    }),
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
            'index' => Pages\Index::route('/'),
            'create' => Pages\Create::route('/create'),
            'edit' => Pages\Edit::route('/{record}/edit'),
            'profile' => Pages\Profile::route('/profile'),
        ];
    }

    public static function canCreate(): bool
    {
        return true;
    }
}
