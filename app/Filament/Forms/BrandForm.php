<?php

namespace App\Filament\Forms;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\TextInput;

class BrandForm
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
                ->content('不建议删除品牌，会造成设备关联资产出错，可以通过编辑品牌名称实现。如果必须删除，强烈建议后续修改资产品牌。'),
        ];
    }
}
