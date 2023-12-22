<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class OrganizationForm
{
    /**
     * 创建或编辑.
     */
    public static function createOrEdit(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->label(__('cat/organization.name')),
        ];
    }
}
