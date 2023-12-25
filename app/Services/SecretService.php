<?php

namespace App\Services;

use App\Models\Secret;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class SecretService extends Service
{
    public function __construct(?Secret $secret = null)
    {
        return $this->model = $secret ?? new Secret;
    }

    /**
     * 选单.
     */
    public static function pluckOptions(string $key_column = 'id', array $exclude_ids = []): Collection
    {
        return Secret::query()
            ->whereNotIn($key_column, $exclude_ids)
            ->whereNotIn('status', [5])
            ->get()
            ->mapWithKeys(function (Secret $secret) {
                $title = '';
                $title .= $secret->getAttribute('name');
                $title .= ' | '.$secret->getAttribute('site');
                $title .= ' | '.$secret->getAttribute('username');
                $title .= ' | '.$secret->getAttribute('expired_at');

                return [$secret->getKey() => $title];
            });
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
        $this->model->setAttribute('token', encrypt($data['token']));
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
    public function retire(): void
    {
        try {
            DB::beginTransaction();
            $this->model->hasSecrets()->delete();
            $this->model->setAttribute('status', 5);
            $this->model->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 是否弃用.
     */
    public function isRetired(): bool
    {
        if ($this->model->getAttribute('status') == 5) {
            return true;
        } else {
            return false;
        }
    }
}
