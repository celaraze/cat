<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /*
     * 测试创建用户.
     */
    public function test_create_user()
    {
        $user = User::factory()->create();
        $this->assertNotNull($user);
    }

    /*
     * 测试用户选单.
     */
    public function test_pluck_options()
    {
        User::factory(5)->create();
        $options = UserService::pluckOptions();
        $this->assertObjectHasProperty('items', $options);
    }

    /*
     * 测试删除用户.
     */
    public function test_delete_user()
    {
        $user = User::factory()->create();
        $this->assertTrue($user->delete());
    }

    /*
     * 测试更新用户.
     */
    public function test_update_user()
    {
        $user = User::factory()->create();
        $user->setAttribute('name', 'new_name');
        $user->save();
        $this->assertTrue($user->getAttribute('name') == 'new_name');
    }
}
