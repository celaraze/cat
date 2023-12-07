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

    public function __construct(?User $user = null)
    {
        $this->user = $user ?? new User();
    }

    /**
     * 选单.
     */
    public static function pluckOptions(?string $exclude_column = null, ?array $exclude_array = null): Collection
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
     * @throws Exception
     */
    #[ArrayShape(['name' => 'mixed', 'email' => 'mixed', 'password' => 'string', 'password_verify' => 'mixed'])]
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
     */
    public function changePassword(string $password): User
    {
        $this->user->setAttribute('password', bcrypt($password));
        $this->user->save();

        return $this->user;
    }

    /**
     * 上传头像.
     */
    public function changeAvatar(string $avatar_url): User
    {
        $this->user->setAttribute('avatar_url', $avatar_url);
        $this->user->save();

        return $this->user;
    }

    /**
     * 删除用户.
     *
     * @throws Exception
     */
    public function delete(): ?bool
    {
        if ($this->user->approvalNodes()->count()) {
            throw new Exception('请先在流程中删除以此用户审批的节点');
        }
        if ($this->user->deviceHasUsers()->count()) {
            throw new Exception('请先删除设备分配记录');
        }
        if ($this->user->applicantForms()->whereNotIn('status', [3, 4])->count()) {
            throw new Exception('请先结案此用户的申请表单');
        }
        if ($this->user->approvalForms()->whereNotIn('status', [3, 4])->count()) {
            throw new Exception('请先结案以此用户审批的申请表单');
        }
        if ($this->user->getKey() == auth()->id()) {
            throw new Exception('不能删除自己');
        }

        return $this->user->delete();
    }
}
