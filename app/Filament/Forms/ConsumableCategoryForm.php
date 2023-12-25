<?php

namespace App\Filament\Forms;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;

class ConsumableCategoryForm
{
    /**
     * 创建或编辑.
     *
     * @return array[]
     */
    public static function createOrEdit(): array
    {
        return [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            TextInput::make('name')
                ->label(__('cat/consumable_category.name'))
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
                ->content(__('cat/consumable_category.form.delete_helper')),
        ];
    }
}
