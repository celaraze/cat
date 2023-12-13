<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

class TicketHasTrackForm
{
    /**
     * 创建.
     */
    public static function create(): array
    {
        return [
            RichEditor::make('comment')
                ->required()
                ->label('评论'),
            TextInput::make('minutes')
                ->numeric()
                ->minValue(1)
                ->hint('单位：分钟')
                ->label('工时'),
        ];
    }
}
