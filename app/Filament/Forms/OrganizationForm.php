<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class OrganizationForm
{
    /**
     * 创建和编辑组织表单.
     */
    public static function createOrganization(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->label('名称'),
        ];
    }
}
