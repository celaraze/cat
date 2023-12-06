<?php

namespace App\Services;

use App\Models\DeviceHasSoftware;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class DeviceHasSoftwareService
{
    public DeviceHasSoftware $device_has_software;

    public function __construct(?DeviceHasSoftware $device_has_software = null)
    {
        $this->device_has_software = $device_has_software ?? new DeviceHasSoftware();
    }

    /**
     * 删除软件管理记录.
     *
     * @throws Exception
     */
    #[ArrayShape(['user_id' => 'int', 'status' => 'string'])]
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
     */
    public function isDeleted(): bool
    {
        if ($this->device_has_software->getAttribute('deleted_at')) {
            return true;
        }

        return false;
    }
}
