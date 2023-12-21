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
                ->label(__('cat.name'))
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
                ->content(__('cat.form.delete_part_category_helper')),
        ];
    }
}
