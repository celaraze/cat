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
                ->label(__('cat.vendor_has_contact.name')),
            TextInput::make('phone_number')
                ->maxLength(255)
                ->required()
                ->label(__('cat.vendor_has_contact.phone_number')),
            TextInput::make('email')
                ->maxLength(255)
                ->required()
                ->rules(['email'])
                ->label(__('cat.vendor_has_contact.email')),
            Repeater::make('additional')
                ->schema([
                    TextInput::make('name')
                        ->label(__('cat.vendor_has_contact.additional.name')),
                    TextInput::make('text')
                        ->label(__('cat.vendor_has_contact.additional.text')),
                ])
                ->defaultItems(0)
                ->label(__('cat.vendor_has_contact.additional')),
        ];
    }
}
