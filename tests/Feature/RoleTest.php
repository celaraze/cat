<?php

namespace Tests\Feature;

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
        $options = RoleService::pluckOptions();
        $this->assertObjectHasProperty('items', $options);
    }
}
