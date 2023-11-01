<?php

namespace Tests\Feature;

use App\Models\AssetNumberRule;
use App\Services\AssetNumberRuleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetNumberRuleTest extends TestCase
{
    use RefreshDatabase;

    /*
     * 测试创建.
     */
    public function test_create(): void
    {
        $asset_number_rule = AssetNumberRule::factory()->create();
        $data = $asset_number_rule->toArray();
        $asset_number_rule->forceDelete();
        $asset_number_rule_service = new AssetNumberRuleService();
        $asset_number_rule = $asset_number_rule_service->create($data);
        $this->assertModelExists($asset_number_rule);
    }
}
