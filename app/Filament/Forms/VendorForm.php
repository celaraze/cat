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

    /**
     * 删除.
     */
    public static function delete(): array
    {
        return [
            Shout::make('hint')
                ->color('danger')
                ->content('不建议删除厂商，会造成资产关联厂商出错，可以通过编辑厂商名称实现。如果必须删除，强烈建议后续修改资产厂商。'),
            Shout::make('hint')
                ->color('danger')
                ->content('删除厂商会同时删除附属联系人。'),
        ];
    }
}
