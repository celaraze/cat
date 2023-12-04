<?php

namespace App\Services;

use App\Models\OrganizationHasUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class UserService
{
    public User $user;

    public function __construct(User $user = null)
    {
        $this->user = $user ?? new User();
    }

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

    /**
     * 创建用户.
     *
     * @param array $data
     * @return User
     * @throws Exception
     */
    #[ArrayShape(['name' => "mixed", 'email' => "mixed", 'password' => "string", 'password_verify' => "mixed"])]
    public function create(array $data): User
    {
        $this->user->setAttribute('name', $data['name']);
        $this->user->setAttribute('email', $data['email']);
        if ($data['password'] != $data['password_verify']) {
            throw new Exception('密码不一致');
        }
        $this->user->setAttribute('password', bcrypt($data['password']));
        $this->user->save();
        return $this->user;
    }

    /**
     * 重置密码.
     *
     * @return User
     */
    public function resetPassword(): User
    {
        $this->user->setAttribute('password', null);
        $this->user->save();
        return $this->user;
    }
}
