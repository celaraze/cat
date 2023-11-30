<?php

namespace App\Services;

use App\Models\DeviceHasSoftware;
use Exception;

class DeviceHasSoftwareService
{
    public DeviceHasSoftware $device_has_software;

    public function __construct(DeviceHasSoftware $device_has_software = null)
    {
        if ($device_has_software) {
            return $this->device_has_software = $device_has_software;
        } else {
            return $this->device_has_software = new DeviceHasSoftware();
        }
    }

    /**
     * 删除软件管理记录.
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function delete(array $data): void
    {
        $new_device_has_software = $this->device_has_software->replicate();
        $new_device_has_software->save();
        $new_device_has_software->update($data);
        $new_device_has_software->delete();
        $this->device_has_software->delete();
    }

    /**
     * 判断是否已经删除的记录.
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        if ($this->device_has_software->getAttribute('deleted_at')) {
            return true;
        }
        return false;
    }
}
