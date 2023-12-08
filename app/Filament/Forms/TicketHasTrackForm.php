<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\RichEditor;

class TicketHasTrackForm
{
    /**
     * 创建.
     */
    public static function create(): array
    {
        return [
            RichEditor::make('comment')
                ->label('评论')
                ->required(),
        ];
    }
}
