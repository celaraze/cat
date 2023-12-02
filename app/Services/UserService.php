<?php

namespace App\Services;

use App\Models\OrganizationHasUser;
use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    /**
     * 选单.
     */
    public static function pluckOptions(string $exclude_column = null, array $exclude_array = null): Collection
    {
        $query = User::query();
        if ($exclude_column && $exclude_array) {
            $query->whereNotIn($exclude_column, $exclude_array);
        }

        return $query->pluck('name', 'id');
    }

    /**
     * 已经有组织的用户.
     */
    public static function existOrganizationHasUserIds(): array
    {
        return OrganizationHasUser::query()->pluck('user_id')->toArray();
    }
}
