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
                ->label(__('cat.comment')),
            TextInput::make('minutes')
                ->numeric()
                ->minValue(1)
                ->hint(__('cat.ticket.minutes'))
                ->label(__('cat.ticket.minutes')),
        ];
    }
}
