<?php

namespace App\Filament\Resources;

class PermissionResource extends \Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource
{
    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = '基础数据';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
