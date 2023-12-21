<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class VendorHasContactForm
{
    /**
     * 创建.
     */
    public static function createOrEdit(): array
    {
        return [

            //region 文本 名称 name
            TextInput::make('name')
                ->maxLength(255)
                ->required()
                ->label(__('cat.name')),
            //endregion

            //region 文本 电话 phone_number
            TextInput::make('phone_number')
                ->maxLength(255)
                ->required()
                ->label(__('cat.phone_number')),
            //endregion

            //region 文本 邮箱 email
            TextInput::make('email')
                ->maxLength(255)
                ->required()
                ->rules(['email'])
                ->label(__('cat.email')),
            //endregion

            //region 数组 额外信息 additional
            Repeater::make('additional')
                ->schema([
                    TextInput::make('name')
                        ->label(__('cat.name')),
                    TextInput::make('text')
                        ->label(__('cat.text')),
                ])
                ->defaultItems(0)
                ->label(__('cat.additional')),
            //endregion
        ];
    }
}
