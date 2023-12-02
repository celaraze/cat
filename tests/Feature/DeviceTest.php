<?php

namespace Feature;

use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_device()
    {
        $device = Device::factory()->create();
        $this->assertNotNull($device);
    }

    public function test_update_device()
    {
        $device = Device::factory()->create();
        $device->setAttribute('name', 'new_name');
        $this->assertTrue($device->save());
    }

    public function test_delete_device()
    {
        $device = Device::factory()->create();
        $device->service()->delete();
        $this->assertTrue(true);
    }

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
}
