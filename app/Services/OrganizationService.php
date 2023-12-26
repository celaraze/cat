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

    #[ArrayShape(['name' => 'string'])]
    public function update(array $data): Organization
    {
        $this->model->update($data);

        return $this->model;
    }

    /**
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

    #[ArrayShape(['name' => 'string', 'creator_id' => 'int'])]
    public function create(array $data): Organization
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();

        return $this->model;
    }
}
