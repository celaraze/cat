<?php

namespace App\Filament\Forms;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;

class VendorForm
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
                ->label(__('cat/vendor.name'))
                ->required(),
            TextInput::make('address')
                ->label(__('cat/vendor.address'))
                ->required(),
            TextInput::make('public_phone_number')
                ->label(__('cat/vendor.public_phone_number')),
            TextInput::make('referrer')
                ->label(__('cat/vendor.referrer')),
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
                ->content(__('cat/vendor.form.delete_helper_1')),
            Shout::make('hint')
                ->color('danger')
                ->content(__('cat/vendor.form.delete_helper_2')),
        ];
    }
}
