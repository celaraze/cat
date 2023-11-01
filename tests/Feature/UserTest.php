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
     * 测试用户选单.
     */
    public function test_pluck_options()
    {
        User::factory(5)->create();
        $options = UserService::pluckOptions();
        $this->assertObjectHasProperty('items', $options);
    }
}
