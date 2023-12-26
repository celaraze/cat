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

    public static function existUserIds(): array
    {
        return OrganizationHasUser::query()->pluck('user_id')->toArray();
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'organization_id' => 'int',
        'user_ids' => 'array',
        'creator_id' => 'int',
    ])]
    public function batchCreate(array $data): void
    {
        try {
            DB::beginTransaction();
            $user_ids = $data['user_ids'];
            foreach ($user_ids as $user_id) {
                $data = [
                    'organization_id' => $data['organization_id'],
                    'user_id' => $user_id,
                    'creator_id' => $data['creator_id'],
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
            throw new Exception(__('cat/organization_has_user.exist'));
        }

        $this->model->setAttribute('organization_id', $data['organization_id']);
        $this->model->setAttribute('user_id', $data['user_id']);
        $this->model->setAttribute('creator_id', $data['creator_id']);

        return $this->model;
    }

    public function delete(): void
    {
        $this->model->delete();
    }
}
