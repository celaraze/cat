<?php

namespace Tests\Feature;

use App\Models\AssetNumberRule;
use App\Models\Device;
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

    /*
     * 设置资产编号自动生成.
     */
    public function test_set_auto_rule()
    {
        $asset_number_rule = AssetNumberRule::factory()->create();
        $data = [
            'asset_number_rule_id' => $asset_number_rule->getKey(),
            'is_auto' => true,
            'class_name' => Device::class,
        ];
        $result = AssetNumberRuleService::setAutoRule($data);
        $this->assertTrue($result);
    }

    /*
     * 重置资产编号自动生成配置.
     */
    public function test_reset_auto_rule()
    {
        $asset_number_rule = AssetNumberRule::factory()->create();
        $data = [
            'asset_number_rule_id' => $asset_number_rule->getKey(),
            'is_auto' => true,
            'class_name' => Device::class,
        ];
        AssetNumberRuleService::setAutoRule($data);
        $result = AssetNumberRuleService::resetAutoRule(Device::class);
        $this->assertIsInt($result);
    }
}
