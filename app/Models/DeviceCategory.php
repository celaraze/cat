<?php

namespace App\Models;

use App\Services\DeviceCategoryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'device_categories';

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'category_id', 'id');
    }

    public function service(): DeviceCategoryService
    {
        return new DeviceCategoryService($this);
    }
}
