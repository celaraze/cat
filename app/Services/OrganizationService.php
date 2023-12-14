<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationHasUser;
use App\Traits\HasFootprint;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class OrganizationService
{
    use HasFootprint;

    public Organization $model;

    public function __construct(?Organization $organization = null)
    {
        $this->model = $organization ?? new Organization();
    }

    /**
     * 编辑组织.
     */
    #[ArrayShape(['name' => 'string'])]
    public function update(array $data): Organization
    {
        $this->model->update($data);

        return $this->model;
    }

    /**
     * 删除组织.
     *
     * @throws Exception
     */
    public function delete(): void
    {
        try {
            DB::beginTransaction();
            $this->model->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 批量创建组织用户记录.
     *
     * @throws Exception
     */
    #[ArrayShape(['organization_id' => 'int', 'user_ids' => 'array'])]
    public function createManyHasUsers(array $data): void
    {
        try {
            DB::beginTransaction();
            $user_ids = $data['user_ids'];
            foreach ($user_ids as $user_id) {
                $data = [
                    'organization_id' => $data['organization_id'],
                    'user_id' => $user_id,
                ];
                $this->createHasUser($data);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 新增组织用户记录.
     *
     * @throws Exception
     */
    #[ArrayShape(['organization_id' => 'int', 'user_id' => 'int'])]
    public function createHasUser(array $data): Model
    {
        $organization_has_user = OrganizationHasUser::query()
            ->where('organization_id', $data['organization_id'])
            ->where('user_id', $data['user_id'])
            ->count();
        if ($organization_has_user) {
            throw new Exception('成员记录已存在');
        }

        return $this->model->hasUsers()->create($data);
    }

    /**
     * 创建组织.
     */
    #[ArrayShape(['name' => 'string'])]
    public function create(array $data): Organization
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->save();

        return $this->model;
    }
}
