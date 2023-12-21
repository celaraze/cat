<?php

namespace App\Services;

use App\Models\DeviceHasSecret;
use App\Models\Secret;
use App\Traits\Services\HasFootprint;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class DeviceHasSecretService
{
    use HasFootprint;

    public DeviceHasSecret $model;

    public function __construct(?DeviceHasSecret $device_has_secret = null)
    {
        return $this->model = $device_has_secret ?? new DeviceHasSecret;
    }

    /**
     * 设备附属密钥.
     *
     * @throws Exception
     */
    #[ArrayShape([
        'creator_id' => 'int',
        'status' => 'int',
    ])]
    public function delete(array $data): void
    {
        if ($this->model->getAttribute('deleted_at') == null) {
            try {
                DB::beginTransaction();
                $new_device_has_secret = $this->model->replicate();
                $new_device_has_secret->save();
                $new_device_has_secret->setAttribute('creator_id', $data['creator_id']);
                $new_device_has_secret->setAttribute('status', $data['status']);
                $new_device_has_secret->save();
                $new_device_has_secret->delete();
                /* @var Secret $secret */
                $secret = $this->model->secret()->first();
                $secret->setAttribute('status', 0);
                $secret->save();
                $this->model->delete();
                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
        }
    }

    /**
     * 设备附属密钥.
     *
     * @throws Exception
     */
    #[ArrayShape([
        'device_id' => 'int',
        'secret_id' => 'int',
        'creator_id' => 'int',
        'status' => 'int',
    ])]
    public function create(array $data): DeviceHasSecret
    {
        $exist = DeviceHasSecret::query()
            ->where('device_id', $data['device_id'])
            ->where('secret_id', $data['secret_id'])
            ->count();
        if ($exist) {
            throw new Exception(__('cat.device_has_secret_exist'));
        }
        try {
            DB::beginTransaction();
            $this->model->setAttribute('device_id', $data['device_id']);
            $this->model->setAttribute('secret_id', $data['secret_id']);
            $this->model->setAttribute('creator_id', $data['creator_id']);
            $this->model->setAttribute('status', $data['status']);
            $this->model->save();
            /* @var Secret $secret */
            $secret = $this->model->secret()->first();
            $secret->setAttribute('status', 1);
            $secret->save();
            DB::commit();

            return $this->model;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 判断是否已经删除的记录.
     */
    public function isDeleted(): bool
    {
        if ($this->model->getAttribute('deleted_at')) {
            return true;
        }

        return false;
    }
}
