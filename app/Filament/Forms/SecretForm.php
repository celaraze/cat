<?php

namespace App\Filament\Forms;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

class SecretForm
{
    public static function createOrEdit(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->label('名称'),
            TextInput::make('site')
                ->label('站点'),
            TextInput::make('username')
                ->required()
                ->label('账户'),
            TextInput::make('token')
                ->required()
                ->password()
                ->label('密钥'),
            DatePicker::make('expired_at')
                ->label('过期时间'),
        ];
    }

    /**
     * 强制报废.
     */
    public static function delete(): array
    {
        return [
            Shout::make('hint')
                ->color('danger')
                ->content('此操作将同时删除所含附属记录'),
        ];
    }

    /**
     * 查看密钥.
     */
    public static function viewToken(): array
    {
        return [
            TextInput::make('password')
                ->required()
                ->password()
                ->label('密码'),
        ];
    }
}
