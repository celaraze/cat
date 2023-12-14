<?php

namespace App\Services;

use App\Models\Setting;
use App\Traits\HasFootprint;

class SettingService
{
    use HasFootprint;

    public Setting $model;

    public function __construct(?Setting $setting = null)
    {
        $this->model = $setting ?? new Setting();
    }

    /**
     * 写入配置.
     */
    public function set(string $key, string $value): void
    {
        /* @var Setting $exist  */
        $exist = Setting::query()->where('custom_key', $key)->first();
        if ($exist) {
            $this->model = $exist;
        }
        $this->model->setAttribute('custom_key', $key);
        $this->model->setAttribute('custom_value', $value);
        $this->model->save();
    }
}
