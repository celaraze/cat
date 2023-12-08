<?php

namespace App\Filament\Forms;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\TextInput;

class PartCategoryForm
{
    /**
     * 创建或编辑.
     */
    public static function createOrEdit(): array
    {
        return [
            TextInput::make('name')
                ->label('名称')
                ->maxLength(255)
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
                ->content('不建议删除分类，会造成配件关联类别出错，可以通过编辑分类名称实现。如果必须删除，强烈建议后续修改配件分类。'),
        ];
    }
}
