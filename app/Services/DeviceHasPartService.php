<?php

namespace App\Services;

use App\Models\DeviceHasPart;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class DeviceHasPartService
{
    public DeviceHasPart $device_has_part;

    public function __construct(?DeviceHasPart $device_has_part = null)
    {
        $this->device_has_part = $device_has_part ?? new DeviceHasPart();
    }

    /**
     * 判断是否已经删除的记录.
     */
    public function isDeleted(): bool
    {
        if ($this->device_has_part->getAttribute('deleted_at')) {
            return true;
        }

        return false;
    }

    /**
     * 删除配件管理记录.
     *
     * @throws Exception
     */
    #[ArrayShape(['user_id' => 'int', 'status' => 'string'])]
    public function delete(array $data): void
    {
        $new_device_has_part = $this->device_has_part->replicate();
        $new_device_has_part->save();
        $new_device_has_part->update($data);
        $new_device_has_part->delete();
        $this->device_has_part->delete();
    }
}
