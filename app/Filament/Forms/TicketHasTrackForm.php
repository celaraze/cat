<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Hidden;
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
            Hidden::make('creator_id')
                ->default(auth()->id()),
            Hidden::make('user_id')
                ->default(auth()->id()),
            RichEditor::make('comment')
                ->required()
                ->label(__('cat/ticket_has_track.comment')),
            TextInput::make('minutes')
                ->numeric()
                ->minValue(1)
                ->hint(__('cat/ticket_has_track.form.minutes.create_helper'))
                ->label(__('cat/ticket_has_track.minutes')),
        ];
    }
}
