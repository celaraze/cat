<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class VendorForm
{
    /**
     * 创建或编辑.
     */
    public static function createOrEdit(): array
    {
        return [
            TextInput::make('name')
                ->label('名称')
                ->required(),
            TextInput::make('address')
                ->label('地址')
                ->required(),
            TextInput::make('public_phone_number')
                ->label('对公电话'),
            TextInput::make('referrer')
                ->label('引荐人'),
        ];
    }
}
