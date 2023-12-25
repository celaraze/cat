<?php

namespace App\Services;

use App\Models\DeviceCategory;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class DeviceCategoryService extends Service
{
    public function __construct(?DeviceCategory $device_category = null)
    {
        $this->model = $device_category ?? new DeviceCategory();
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
        $this->model->setAttribute('name', $data['name']);
        $this->model->save();

        return $this->model;
    }

    /**
     * 删除设备分类.
     */
    public function delete(): ?bool
    {
        return $this->model->delete();
    }
}
