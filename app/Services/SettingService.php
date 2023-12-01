<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    public Setting $setting;

    public function __construct(Setting $setting = null)
    {
        if ($setting) {
            $this->setting = $setting;
        } else {
            $this->setting = new Setting();
        }
    }

    /**
     * 写入配置.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function set(string $key, string $value): void
    {
        /* @var $exist Setting */
        $exist = Setting::query()->where('custom_key', $key)->first();
        if ($exist) {
            $this->setting = $exist;
        }
        $this->setting->setAttribute('custom_key', $key);
        $this->setting->setAttribute('custom_value', $value);
        $this->setting->save();
    }
}
