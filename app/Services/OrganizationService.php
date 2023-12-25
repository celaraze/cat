<?php

namespace App\Services;

use App\Models\Organization;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class OrganizationService extends Service
{
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
