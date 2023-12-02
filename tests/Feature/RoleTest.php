<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /*
     * 测试选单.
     */
    public function test_pluck_options()
    {
        Role::factory(5)->create();
        $options = RoleService::pluckOptions();
        $this->assertObjectHasProperty('items', $options);
    }

    /*
     * 测试创建角色.
     */
    public function test_create_role()
    {
        $role = Role::factory()->create();
        $this->assertNotNull($role);
    }

    /*
     * 测试更新角色.
     */
    public function test_update_role()
    {
        $role = Role::factory()->create();
        $role->setAttribute('name', 'new_name');
        $role->save();
        $this->assertTrue($role->getAttribute('name') == 'new_name');
    }
}
