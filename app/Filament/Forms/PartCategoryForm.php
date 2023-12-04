<?php

namespace App\Filament\Forms;

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
}
