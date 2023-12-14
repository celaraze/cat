<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceHasUser;
use App\Traits\HasFootprint;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class DeviceHasUserService
{
    use HasFootprint;

    public DeviceHasUser $model;

    public function __construct(?DeviceHasUser $device_has_user = null)
    {
        $this->model = $device_has_user ?? new DeviceHasUser();
    }

    /**
     * 创建.
     *
     * @throws Exception
     */
    #[ArrayShape([
        'device_id' => 'int',
        'user_id' => 'int',
        'status' => 'int',
        'comment' => 'string',
        'expired_at' => 'string',
        'operator_id' => 'int',
    ])]
    public function create(array $data): DeviceHasUser
    {
        try {
            DB::beginTransaction();
            $this->model->setAttribute('device_id', $data['device_id']);
            $this->model->setAttribute('user_id', $data['user_id']);
            $this->model->setAttribute('status', $data['status']);
            $this->model->setAttribute('comment', $data['comment']);
            $this->model->setAttribute('expired_at', $data['expired_at']);
            $this->model->setAttribute('operator_id', $data['operator_id']);
            $this->model->save();
            /* @var Device $device */
            $device = $this->model->device()->first();
            $device->setAttribute('status', $data['status']);
            $device->save();
            DB::commit();

            return $this->model;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 删除.
     *
     * @throws Exception
     */
    #[ArrayShape([
        'delete_comment' => 'string',
    ])]
    public function delete(array $data): void
    {
        try {
            DB::beginTransaction();
            $this->model->setAttribute('delete_comment', $data['delete_comment']);
            $this->model->save();
            $this->model->delete();
            /* @var Device $device */
            $device = $this->model->device()->first();
            $device->setAttribute('status', 0);
            $device->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
