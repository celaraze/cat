<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class TicketCategoryForm
{
    /**
     * 创建或更新.
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
