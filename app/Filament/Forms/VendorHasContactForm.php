<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class VendorHasContactForm
{
    /**
     * 创建或编辑.
     */
    public static function createOrEdit(): array
    {
        return [
            TextInput::make('name')
                ->maxLength(255)
                ->required()
                ->label(__('cat.name')),
            TextInput::make('phone_number')
                ->maxLength(255)
                ->required()
                ->label(__('cat.phone_number')),
            TextInput::make('email')
                ->maxLength(255)
                ->required()
                ->rules(['email'])
                ->label(__('cat.email')),
            Repeater::make('additional')
                ->schema([
                    TextInput::make('name')
                        ->label(__('cat.name')),
                    TextInput::make('text')
                        ->label(__('cat.text')),
                ])
                ->defaultItems(0)
                ->label(__('cat.additional')),
        ];
    }
}
