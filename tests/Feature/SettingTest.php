<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    /*
     * 测试创建.
     */
    public function test_create()
    {
        $setting = Setting::factory()->create();
        $this->assertModelExists($setting);
    }

    /*
     * 测试获取值.
     */
    public function test_get_value()
    {
        $setting = Setting::factory()->create([
            'custom_key' => 'test_key',
            'custom_value' => 'test_value'
        ]);
        $this->assertTrue($setting->getAttribute('custom_key') == 'test_key');
    }
}
