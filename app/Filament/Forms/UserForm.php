<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class UserForm
{
    /**
     * 创建.
     */
    public static function createOrEdit(): array
    {
        return [
            TextInput::make('name')
                ->label('名称')
                ->maxLength(255)
                ->required(),
            TextInput::make('email')
                ->label('邮箱')
                ->maxLength(255),
            Select::make('roles')
                ->label('角色')
                ->multiple()
                ->relationship('roles', 'name')
                ->preload(),
        ];
    }
}
