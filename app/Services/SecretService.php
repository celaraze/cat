<?php

namespace App\Services;

use App\Models\Secret;
use App\Traits\HasFootprint;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class SecretService
{
    use HasFootprint;

    public Secret $model;

    public function __construct(?Secret $secret = null)
    {
        return $this->model = $secret ?? new Secret;
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return Secret::query()->pluck('name', 'id');
    }

    /**
     * 创建.
     */
    #[ArrayShape([
        'name' => 'string',
        'username' => 'string',
        'token' => 'string',
        'site' => '?string',
        'vault' => 'string',
        'expired_at' => 'string',
        'creator_id' => 'int',
    ])]
    public function create(array $data): Secret
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('username', $data['username']);
        $this->model->setAttribute('token', $data['token']);
        $this->model->setAttribute('site', $data['site']);
        $this->model->setAttribute('vault', 'public');
        $this->model->setAttribute('expired_at', $data['expired_at']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->setAttribute('status', 0);
        $this->model->save();

        return $this->model;
    }

    /**
     * 删除.
     *
     * @throws Exception
     */
    public function delete(): void
    {
        try {
            DB::beginTransaction();
            $this->model->hasSecrets()->delete();
            $this->model->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
