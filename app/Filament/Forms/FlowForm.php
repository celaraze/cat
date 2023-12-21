<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class FlowForm
{
    /**
     * 创建或编辑流程的表单.
     */
    public static function create(): array
    {
        return [
            TextInput::make('name')
                ->label(__('cat.name'))
                ->required()
                ->unique(),
        ];
    }
}
