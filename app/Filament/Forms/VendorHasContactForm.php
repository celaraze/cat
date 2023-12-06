<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class VendorHasContactForm
{
    /**
     * 创建.
     */
    public static function createOrEdit(): array
    {
        return [
            TextInput::make('name')
                ->maxLength(255)
                ->required()
                ->label('名称'),
            TextInput::make('phone_number')
                ->maxLength(255)
                ->required()
                ->label('电话'),
            TextInput::make('email')
                ->maxLength(255)
                ->label('邮箱'),
        ];
    }
}
