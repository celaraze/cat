<?php

namespace App\Filament\Forms;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class UserForm
{
    /**
     * 创建.
     */
    public static function create(): array
    {
        return [
            TextInput::make('name')
                ->label('名称')
                ->unique()
                ->required(),
            TextInput::make('email')
                ->label('邮箱')
                ->rules(['email'])
                ->required(),
            Shout::make('')
                ->color('warning')
                ->content('新建用户的默认密码为 cat ，请提醒用户及时修改密码。'),
        ];
    }

    /**
     * 编辑.
     */
    public static function edit(): array
    {
        return [
            TextInput::make('name')
                ->label('名称')
                ->unique()
                ->required(),
            TextInput::make('email')
                ->label('邮箱')
                ->rules(['email'])
                ->required(),
            Select::make('roles')
                ->label('角色')
                ->multiple()
                ->relationship('roles', 'name')
                ->searchable()
                ->preload(),
        ];
    }

    /**
     * 修改密码.
     */
    public static function changePassword(): array
    {
        return [
            TextInput::make('password')
                ->label('新密码')
                ->password()
                ->required(),
            TextInput::make('password-verify')
                ->label('确认密码')
                ->password()
                ->required(),
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
                ->content('删除用户前，请先确认已处理以下内容：'),
            Shout::make('')
                ->color('warning')
                ->content(function () use ($bool) {
                    $icon = $bool['device_has_users'] ? '✔' : '✖';

                    return $icon . ' 此用户没有正在管理的设备';
                }),
            Shout::make('')
                ->color('warning')
                ->content(function () use ($bool) {
                    $icon = $bool['applicant_forms'] ? '✔' : '✖';

                    return $icon . ' 此用户没有尚未结案的申请表单';
                }),
            Shout::make('')
                ->color('warning')
                ->content(function () use ($bool) {
                    $icon = $bool['approve_forms'] ? '✔' : '✖';

                    return $icon . ' 此用户没有尚未结案的审批表单';
                }),
            Shout::make('')
                ->color('warning')
                ->content(function () use ($bool) {
                    $icon = $bool['approve_nodes'] ? '✔' : '✖';

                    return $icon . ' 此用户没有正在审批的节点';
                }),
        ];
    }

    /**
     * 修改密码.
     *
     * @return array
     */
    public static function resetPassword(): array
    {
        return [
            Shout::make('')
                ->color('warning')
                ->content('重置后用户的默认密码为 cat ，请提醒用户及时修改密码。'),
        ];
    }
}
