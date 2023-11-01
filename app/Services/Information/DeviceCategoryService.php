<?php

namespace App\Services\Information;

use App\Models\Information\DeviceCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DeviceCategoryService
{
    public DeviceCategory $device_category;

    public function __construct(DeviceCategory $device_category = null)
    {
        if ($device_category) {
            $this->device_category = $device_category;
        } else {
            $this->device_category = new DeviceCategory();
        }
    }

    /**
     * 选单.
     *
     * @return Collection
     */
    public static function pluckOptions(): Collection
    {
        return DeviceCategory::query()
            ->pluck('name', 'id');
    }

    /**
     * 通过名称获取模型.
     *
     * @param string $category_name
     * @return Model|Builder|null
     */
    public static function getModelByName(string $category_name): Model|Builder|null
    {
        return DeviceCategory::query()->where('name', $category_name)->first();
    }

    /**
     * 创建设备分类.
     *
     * @param array $data
     * @return DeviceCategory
     */
    public function create(array $data): DeviceCategory
    {
        $this->device_category->setAttribute('name', $data['name']);
        $this->device_category->save();
        return $this->device_category;
    }
}
