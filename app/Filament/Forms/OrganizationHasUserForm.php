<?php

namespace App\Filament\Forms;

use App\Services\OrganizationHasUserService;
use App\Services\UserService;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;

class OrganizationHasUserForm
{
    /**
     * 附加.
     */
    public static function create(): array
    {
        return [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            Select::make('user_ids')
                ->label(__('cat/organization_has_user.user_ids'))
                ->options(UserService::pluckOptions('id', OrganizationHasUserService::existUserIds()))
                ->multiple()
                ->searchable(),
        ];
    }
}
