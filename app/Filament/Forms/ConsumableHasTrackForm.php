<?php

namespace App\Filament\Forms;

use App\Models\Consumable;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;

class ConsumableHasTrackForm
{
    public static function create(Consumable $consumable): array
    {
        $unit_name = $consumable->unit()->first()?->getAttribute('name') ?? null;

        return [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            TextInput::make('quantity')
                ->numeric()
                ->required()
                ->hint(__('cat/consumable_has_track.form.quantity_helper'))
                ->label(__('cat/consumable_has_track.quantity')),
            TextInput::make('unit')
                ->placeholder($unit_name)
                ->readOnly()
                ->hint(__('cat/consumable_has_track.form.unit_helper'))
                ->label(__('cat/consumable_has_track.unit')),
            TextInput::make('comment')
                ->required()
                ->label(__('cat/consumable_has_track.comment')),
        ];
    }
}
