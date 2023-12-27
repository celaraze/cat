<?php

namespace App\Filament\Forms;

use App\Enums\FlowHasFormEnum;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;

class FlowHasFormForm
{
    public static function approve(): array
    {
        return [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            Hidden::make('approver_id')
                ->default(auth()->id()),
            TextInput::make('comment')
                ->label(__('cat/flow_has_form.comment')),
            Radio::make('status')
                ->required()
                ->options(FlowHasFormEnum::allApproveText())
                ->label(__('cat/flow_has_form.status')),
        ];
    }
}
