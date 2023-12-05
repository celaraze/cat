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
                ->label('名称')
                ->required()
                ->unique(),
        ];
    }
}
