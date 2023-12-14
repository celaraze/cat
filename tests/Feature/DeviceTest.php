<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Part;
use App\Models\User;
use App\Services\DeviceHasPartService;
use App\Services\DeviceHasUserService;
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
        $device->service()->retire();
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
            'creator_id' => $user->getKey(),
            'status' => 0,
            'comment' => '测试类设备分配用户',
            'expired_at' => null,
        ];
        $device_has_user_service = new DeviceHasUserService();
        $result = $device_has_user_service->create($data);
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
            'creator_id' => $user->getKey(),
            'status' => 1,
            'comment' => '测试类设备分配用户',
            'expired_at' => null,
        ];
        $device_has_user_service = new DeviceHasUserService();
        $device_has_user = $device_has_user_service->create($data);
        $data['delete_comment'] = 'test_delete';
        $result = $device_has_user->service()->delete($data);
        $this->assertTrue(true);
    }

    /*
     * 创建设备配件.
     */
    public function test_create_has_part()
    {
        $device = Device::factory()->create();
        $part = Part::factory()->create();
        $user = User::factory()->create();
        $data = [
            'device_id' => $device->getKey(),
            'part_id' => $part->getKey(),
            'creator_id' => $user->getKey(),
            'status' => 0,
        ];
        $device_has_part_service = new DeviceHasPartService();
        $result = $device_has_part_service->create($data);
        $this->assertNotNull($result);
    }
}
