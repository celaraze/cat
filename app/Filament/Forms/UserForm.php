<?php

namespace App\Filament\Forms;

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
}
