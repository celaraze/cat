<?php

namespace Feature;

use App\Models\Device;
use App\Models\Part;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    /*
     * 创建设备.
     */
    public function test_create_device()
    {
        $device = Device::factory()->create();
        $this->assertNotNull($device);
    }

    /*
     * 更新设备.
     */
    public function test_update_device()
    {
        $device = Device::factory()->create();
        $device->setAttribute('name', 'new_name');
        $this->assertTrue($device->save());
    }

    /*
     * 删除设备，报废.
     */
    public function test_delete_device()
    {
        $device = Device::factory()->create();
        $device->service()->delete();
        $this->assertTrue(true);
    }

    /*
     * 创建设备用户.
     */
    public function test_create_has_user()
    {
        $device = Device::factory()->create();
        $user = User::factory()->create();
        $data = [
            'device_id' => $device->getKey(),
            'user_id' => $user->getKey(),
        ];
        $result = $device->service()->createHasUser($data);
        $this->assertNotNull($result);
    }

    /*
     * 删除设备用户.
     */
    public function test_delete_has_user()
    {
        $device = Device::factory()->create();
        $user = User::factory()->create();
        $data = [
            'device_id' => $device->getKey(),
            'user_id' => $user->getKey(),
        ];
        $device->service()->createHasUser($data);
        $data['delete_comment'] = 'test_delete';
        $result = $device->service()->deleteHasUser($data);
        $this->assertIsInt($result);
    }

    /*
     * 创建设备配件.
     */
    public function test_create_device_has_part()
    {
        $device = Device::factory()->create();
        $part = Part::factory()->create();
        $user = User::factory()->create();
        $data = [
            'part_id' => $part->getKey(),
            'user_id' => $user->getKey(),
            'status' => '附加',
        ];
        $result = $device->service()->createHasPart($data);
        $this->assertNotNull($result);
    }
}
