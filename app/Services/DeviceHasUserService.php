<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceHasUser;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class DeviceHasUserService
{
    public DeviceHasUser $device_has_user;

    public function __construct(?DeviceHasUser $device_has_user = null)
    {
        $this->device_has_user = $device_has_user ?? new DeviceHasUser();
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
    ])]
    public function create(array $data): DeviceHasUser
    {
        try {
            DB::beginTransaction();
            $this->device_has_user->setAttribute('device_id', $data['device_id']);
            $this->device_has_user->setAttribute('user_id', $data['user_id']);
            $this->device_has_user->setAttribute('status', $data['status']);
            $this->device_has_user->setAttribute('comment', $data['comment']);
            $this->device_has_user->setAttribute('expired_at', $data['expired_at']);
            $this->device_has_user->save();
            /* @var Device $device */
            $device = $this->device_has_user->device()->first();
            $device->setAttribute('status', $data['status']);
            $device->save();
            DB::commit();

            return $this->device_has_user;
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
            $this->device_has_user->setAttribute('delete_comment', $data['delete_comment']);
            $this->device_has_user->save();
            $this->device_has_user->delete();
            /* @var Device $device */
            $device = $this->device_has_user->device()->first();
            $device->setAttribute('status', 0);
            $device->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
