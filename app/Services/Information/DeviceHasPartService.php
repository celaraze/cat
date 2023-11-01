<?php

namespace App\Services\Information;

use App\Models\Information\DeviceHasPart;
use Exception;

class DeviceHasPartService
{
    public DeviceHasPart $device_has_part;

    public function __construct(DeviceHasPart $device_has_part = null)
    {
        if ($device_has_part) {
            $this->device_has_part = $device_has_part;
        } else {
            $this->device_has_part = new DeviceHasPart();
        }
    }

    /**
     * 判断是否已经删除的记录.
     *
     * @return bool
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
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function delete(array $data): void
    {
        $new_device_has_part = $this->device_has_part->replicate();
        $new_device_has_part->save();
        $new_device_has_part->update($data);
        $new_device_has_part->delete();
        $this->device_has_part->delete();
    }
}
