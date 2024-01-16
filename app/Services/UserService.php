<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class UserService extends Service
{
    public function __construct(?User $user = null)
    {
        $this->model = $user ?? new User();
    }

    public static function pluckOptions(?string $exclude_column = null, ?array $exclude_array = null): Collection
    {
        $query = User::query();
        if ($exclude_column && $exclude_array) {
            $query->whereNotIn($exclude_column, $exclude_array);
        }

        return $query->pluck('name', 'id');
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'name' => 'mixed',
        'email' => 'mixed',
        'password' => 'string',
        'password_verify' => 'mixed',
        'roles' => 'array',
        'creator_id' => 'int',
    ])]
    public function create(array $data): User
    {
        try {
            DB::beginTransaction();
            $this->model->setAttribute('name', $data['name']);
            $this->model->setAttribute('email', $data['email']);
            if ($data['password'] != $data['password_verify']) {
                throw new Exception(__('cat/password_not_match'));
            }
            $this->model->setAttribute('password', bcrypt($data['password']));
            $this->model->setAttribute('creator_id', $data['creator_id']);
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

    public function changePassword(string $password): User
    {
        $this->model->setAttribute('password', bcrypt($password));
        $this->model->save();

        return $this->model;
    }

    public function changeAvatar(string $avatar_url): User
    {
        $this->model->setAttribute('avatar_url', $avatar_url);
        $this->model->save();

        return $this->model;
    }

    /**
     * @throws Exception
     */
    public function delete(): ?bool
    {
        if ($this->model->deviceHasUsers()->count()) {
            throw new Exception(__('cat/user.delete_failure_device_has_user'));
        }
        if ($this->model->applicantForms()->whereNotIn('status', [3, 4])->count()) {
            throw new Exception(__('cat/user.delete_failure_applicant_form'));
        }
        if ($this->model->approvalForms()->whereNotIn('status', [3, 4])->count()) {
            throw new Exception(__('cat/user.delete_failure_approval_form'));
        }
        if ($this->model->getKey() == auth()->id()) {
            throw new Exception(__('cat/user.delete_failure_self'));
        }

        return $this->model->delete();
    }

    /**
     * @throws Exception
     */
    public function forceDelete(): ?bool
    {
        if (! $this->model->service()->isDeleted()) {
            throw new Exception(__('cat/user.force_delete_failure_not_deleted'));
        }

        return $this->model->forceDelete();
    }

    /**
     * @throws Exception
     */
    public function update(array $data): User
    {
        $is_exist = User::query()->where('email', $data['email'])
            ->where('id', '!=', $this->model->getKey())
            ->exists();
        if ($is_exist) {
            throw new Exception(__('cat/user.email_already_exist'));
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
