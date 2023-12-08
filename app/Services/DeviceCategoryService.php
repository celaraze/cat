<?php

namespace App\Services;

use App\Models\DeviceCategory;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class DeviceCategoryService
{
    public DeviceCategory $device_category;

    public function __construct(?DeviceCategory $device_category = null)
    {
        $this->device_category = $device_category ?? new DeviceCategory();
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return DeviceCategory::query()
            ->pluck('name', 'id');
    }

    /**
     * 创建设备分类.
     */
    #[ArrayShape(['name' => 'string'])]
    public function create(array $data): DeviceCategory
    {
        $this->device_category->setAttribute('name', $data['name']);
        $this->device_category->save();

        return $this->device_category;
    }

    /**
     * 删除设备分类.
     */
    public function delete(): ?bool
    {
        return $this->device_category->delete();
    }
}
