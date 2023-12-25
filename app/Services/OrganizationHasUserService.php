<?php

namespace App\Services;

use App\Models\OrganizationHasUser;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class OrganizationHasUserService extends Service
{
    public function __construct(?OrganizationHasUser $organization_has_user = null)
    {
        $this->model = $organization_has_user ?? new OrganizationHasUser();
    }

    /**
     * 批量创建组织用户记录.
     *
     * @throws Exception
     */
    #[ArrayShape(['organization_id' => 'int', 'user_ids' => 'array'])]
    public function batchCreate(array $data): void
    {
        try {
            DB::beginTransaction();
            $user_ids = $data['user_ids'];
            foreach ($user_ids as $user_id) {
                $data = [
                    'organization_id' => $data['organization_id'],
                    'user_id' => $user_id,
                ];
                $this->create($data);
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
    public function create(array $data): Model
    {
        $organization_has_user = OrganizationHasUser::query()
            ->where('organization_id', $data['organization_id'])
            ->where('user_id', $data['user_id'])
            ->count();
        if ($organization_has_user) {
            throw new Exception(__('cat/organization_has_user_exists'));
        }

        $this->model->setAttribute('organization_id', $data['organization_id']);
        $this->model->setAttribute('user_id', $data['user_id']);

        return $this->model;
    }

    /**
     * 删除组织用户记录.
     */
    public function delete(): void
    {
        $this->model->delete();
    }
}
