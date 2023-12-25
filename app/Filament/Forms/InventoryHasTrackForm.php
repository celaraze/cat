<?php

namespace App\Filament\Forms;

use App\Enums\InventoryEnum;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;

class InventoryHasTrackForm
{
    /**
     * 盘点.
     */
    public static function check(): array
    {
        return [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            Radio::make('check')
                ->options(InventoryEnum::allCheckText())
                ->label(__('cat/inventory_has_track.check'))
                ->required(),
            TextInput::make('comment')
                ->label(__('cat/inventory_has_track.comment')),
        ];
    }
}
