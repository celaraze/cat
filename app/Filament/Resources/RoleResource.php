<?php

namespace App\Filament\Resources;

class RoleResource extends \Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource
{
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = '基础数据';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
