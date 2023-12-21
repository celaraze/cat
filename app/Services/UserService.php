<?php

namespace App\Services;

use App\Models\OrganizationHasUser;
use App\Models\User;
use App\Traits\Services\HasFootprint;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class UserService
{
    use HasFootprint;

    public User $model;

    public function __construct(?User $user = null)
    {
        $this->model = $user ?? new User();
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
    #[ArrayShape([
        'name' => 'mixed',
        'email' => 'mixed',
        'password' => 'string',
        'password_verify' => 'mixed',
    ])]
    public function create(array $data): User
    {
        try {
            DB::beginTransaction();
            $this->model->setAttribute('name', $data['name']);
            $this->model->setAttribute('email', $data['email']);
            if ($data['password'] != $data['password_verify']) {
                throw new Exception(__('cat.password_not_match'));
            }
            $this->model->setAttribute('password', bcrypt($data['password']));
            $this->model->save();
            // 将 role_ids 转为 int 类型，才能被正确 assign
            $roles = array_map('intval', $data['roles']);
            $this->model->assignRole($roles);
            DB::commit();

            return $this->model;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 重置密码.
     */
    public function changePassword(string $password): User
    {
        $this->model->setAttribute('password', bcrypt($password));
        $this->model->save();

        return $this->model;
    }

    /**
     * 上传头像.
     */
    public function changeAvatar(string $avatar_url): User
    {
        $this->model->setAttribute('avatar_url', $avatar_url);
        $this->model->save();

        return $this->model;
    }

    /**
     * 删除用户.
     *
     * @throws Exception
     */
    public function delete(): ?bool
    {
        if ($this->model->approvalNodes()->count()) {
            throw new Exception(__('cat.user_delete_failure_approval_node'));
        }
        if ($this->model->deviceHasUsers()->count()) {
            throw new Exception(__('cat.user_delete_failure_device_has_user'));
        }
        if ($this->model->applicantForms()->whereNotIn('status', [3, 4])->count()) {
            throw new Exception(__('cat.user_delete_failure_applicant_form'));
        }
        if ($this->model->approvalForms()->whereNotIn('status', [3, 4])->count()) {
            throw new Exception(__('cat.user_delete_failure_approval_form'));
        }
        if ($this->model->getKey() == auth()->id()) {
            throw new Exception(__('cat.user_delete_failure_self'));
        }

        return $this->model->delete();
    }

    /**
     * 永久删除.
     *
     * @throws Exception
     */
    public function forceDelete(): ?bool
    {
        if (!$this->model->service()->isDeleted()) {
            throw new Exception(__('cat.user_force_delete_failure_not_deleted'));
        }

        return $this->model->forceDelete();
    }

    /**
     * 是否删除.
     */
    public function isDeleted(): bool
    {
        if ($this->model->getAttribute('deleted_at') != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 编辑.
     *
     * @throws Exception
     */
    public function update(array $data): User
    {
        $is_exist = User::query()->where('email', $data['email'])
            ->where('id', '!=', $this->model->getKey())
            ->exists();
        if ($is_exist) {
            throw new Exception(__('cat.email_already_exist'));
        }

        try {
            DB::beginTransaction();
            $this->model->setAttribute('name', $data['name']);
            $this->model->setAttribute('email', $data['email']);
            $this->model->save();
            // 将 role_ids 转为 int 类型，才能被正确 sync
            $roles = array_map('intval', $data['roles']);
            $this->model->syncRoles($roles);
            DB::commit();

            return $this->model;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
