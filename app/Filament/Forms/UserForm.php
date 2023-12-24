<?php

namespace App\Filament\Forms;

use App\Models\User;
use App\Services\RoleService;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Ramsey\Uuid\Uuid;

class UserForm
{
    /**
     * 创建.
     */
    public static function create(): array
    {
        return [
            TextInput::make('name')
                ->label(__('cat/user.name'))
                ->required(),
            TextInput::make('email')
                ->label(__('cat/user.email'))
                ->rules(['email'])
                ->required(),
            Select::make('roles')
                ->label(__('cat/user.roles'))
                ->multiple()
                ->options(RoleService::pluckOptions())
                ->searchable()
                ->preload(),
            Shout::make('')
                ->color('warning')
                ->content(__('cat/user.form.create_helper')),
        ];
    }

    /**
     * 编辑.
     */
    public static function edit(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->label(__('cat/user.name')),
            TextInput::make('email')
                ->rules(['email'])
                ->required()
                ->label(__('cat/user.email')),
            // 排除超级管理员角色
            Select::make('roles')
                ->multiple()
                ->relationship(
                    name: 'roles',
                    titleAttribute: 'name',
                    modifyQueryUsing: function (Builder $query) {
                        /* @var User $auth_user */
                        $auth_user = auth()->user();
                        if (! $auth_user->is_super_admin()) {
                            return $query->where('id', '!=', 1);
                        }
                    },
                )
                ->searchable()
                ->preload()
                ->default('roles')
                ->label(__('cat/user.roles')),
        ];
    }

    /**
     * 修改密码.
     */
    public static function changePassword(): array
    {
        return [
            TextInput::make('password')
                ->label(__('cat/user.new_password'))
                ->password()
                ->required(),
            TextInput::make('password-verify')
                ->label(__('cat/user.new_password_verify'))
                ->password()
                ->required(),
        ];
    }

    /**
     * 修改头像.
     */
    public static function changeAvatar(): array
    {
        return [
            FileUpload::make('avatar')
                ->label(__('cat/user.avatar'))
                ->directory('avatars')
                ->getUploadedFileNameForStorageUsing(
                    function (TemporaryUploadedFile $file) {
                        return Uuid::uuid4().'.'.$file->getClientOriginalExtension();
                    }
                ),
        ];
    }

    /**
     * 删除.
     */
    public static function delete(array $bool): array
    {
        return [
            Shout::make('')
                ->color('primary')
                ->content(__('cat/user.form.delete_helper_1')),
            Shout::make('')
                ->color('warning')
                ->content(function () use ($bool) {
                    $icon = $bool['device_has_users'] ? '✔' : '✖';

                    return $icon.__('cat/user.form.delete_helper_2');
                }),
            Shout::make('')
                ->color('warning')
                ->content(function () use ($bool) {
                    $icon = $bool['applicant_forms'] ? '✔' : '✖';

                    return $icon.__('cat/user.form.delete_helper_3');
                }),
            Shout::make('')
                ->color('warning')
                ->content(function () use ($bool) {
                    $icon = $bool['approve_forms'] ? '✔' : '✖';

                    return $icon.__('cat/user.form.delete_helper_4');
                }),
            Shout::make('')
                ->color('warning')
                ->content(function () use ($bool) {
                    $icon = $bool['approve_nodes'] ? '✔' : '✖';

                    return $icon.__('cat/user.form.delete_helper_5');
                }),
        ];
    }

    /**
     * 修改密码.
     */
    public static function resetPassword(): array
    {
        return [
            Shout::make('')
                ->color('warning')
                ->content(__('cat/user.form.reset_password_helper')),
        ];
    }
}
