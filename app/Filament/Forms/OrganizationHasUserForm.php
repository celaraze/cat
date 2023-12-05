<?php

namespace App\Filament\Forms;

use App\Services\UserService;
use Filament\Forms\Components\Select;

class OrganizationHasUserForm
{
    /**
     * 创建.
     */
    public static function create(): array
    {
        return [
            Select::make('user_ids')
                ->label('成员')
                ->options(UserService::pluckOptions('id', UserService::existOrganizationHasUserIds()))
                ->multiple()
                ->searchable(),
        ];
    }
}
