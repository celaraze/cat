<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;

class OrganizationForm
{
    /**
     * 创建或编辑.
     */
    public static function createOrEdit(): array
    {
        return [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            TextInput::make('name')
                ->required()
                ->label(__('cat/organization.name')),
        ];
    }
}
