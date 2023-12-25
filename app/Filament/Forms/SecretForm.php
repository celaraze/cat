<?php

namespace App\Filament\Forms;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;

class SecretForm
{
    /**
     * 创建或编辑.
     */
    public static function createOrEdit(): array
    {
        return [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            TextInput::make('name')
                ->required()
                ->label(__('cat/secret.name')),
            TextInput::make('site')
                ->label(__('cat/secret.site')),
            TextInput::make('username')
                ->required()
                ->label(__('cat/secret.username')),
            TextInput::make('token')
                ->required()
                ->password()
                ->label(__('cat/secret.token')),
            DatePicker::make('expired_at')
                ->label(__('cat/secret.expired_at')),
        ];
    }

    /**
     * 删除.
     */
    public static function delete(): array
    {
        return [
            Shout::make('hint')
                ->color('danger')
                ->content(__('cat/secret.form.delete_helper')),
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
                ->label(__('cat/secret.password')),
        ];
    }
}
