<?php

namespace App\Filament\Forms;

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
                ->label('名称')
                ->required(),
        ];
    }
}
