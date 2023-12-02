<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    /*
     * 测试创建设置记录.
     */
    public function test_create()
    {
        $setting = Setting::factory()->create();
        $this->assertNotNull($setting);
    }

    /*
     * 测试获取值.
     */
    public function test_get_value()
    {
        $setting = Setting::factory()->create([
            'custom_key' => 'test_key',
            'custom_value' => 'test_value',
        ]);
        $this->assertTrue($setting->getAttribute('custom_key') == 'test_key');
    }

    /*
     * 测试更新值.
     */
    public function test_update_value()
    {
        $setting = Setting::factory()->create();
        $setting->setAttribute('custom_value', 'new_value');
        $setting->save();
        $this->assertTrue($setting->getAttribute('custom_value') == 'new_value');
    }
}
