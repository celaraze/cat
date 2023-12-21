<?php

namespace App\Filament\Forms;

use Awcodes\Shout\Components\Shout;
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
                ->label(__('cat.name'))
                ->required(),
            TextInput::make('address')
                ->label(__('cat.address'))
                ->required(),
            TextInput::make('public_phone_number')
                ->label(__('cat.public_phone_number')),
            TextInput::make('referrer')
                ->label(__('cat.referrer')),
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
                ->content(__('cat.form.delete_vendor_helper')),
            Shout::make('hint')
                ->color('danger')
                ->content(__('cat.form.delete_vendor_shout_helper')),
        ];
    }
}
