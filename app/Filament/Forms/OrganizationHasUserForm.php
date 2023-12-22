<?php

namespace App\Filament\Forms;

use App\Services\UserService;
use Filament\Forms\Components\Select;

class OrganizationHasUserForm
{
    /**
     * 附加.
     */
    public static function create(): array
    {
        return [
            Select::make('user_ids')
                ->label(__('cat.organization_has_user.user_ids'))
                ->options(UserService::pluckOptions('id', UserService::existOrganizationHasUserIds()))
                ->multiple()
                ->searchable(),
        ];
    }
}
