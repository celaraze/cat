<?php

namespace App\Services;

use App\Models\AssetNumberRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AssetNumberRuleService
{
    public AssetNumberRule $assetNumberRule;

    public function __construct(AssetNumberRule $assetNumberRule = null)
    {
        if ($assetNumberRule) {
            $this->assetNumberRule = $assetNumberRule;
        } else {
            $this->assetNumberRule = new AssetNumberRule();
        }
    }

    /**
     * 获取资产是否启用自动生成编号.
     */
    public static function isAuto(string $class_name): mixed
    {
        return AssetNumberRule::query()
            ->where('class_name', $class_name)
            ->value('is_auto');
    }

    /**
     * 获取资产对应的资产编号自动生成规则.
     */
    public static function getAutoRule(string $class_name): Model|null|Builder
    {
        return AssetNumberRule::query()
            ->where('class_name', $class_name)
            ->first();
    }

    /**
     * 绑定资产编号自动生成规则.
     */
    public static function setAutoRule(array $data): void
    {
        $asset_number_rule = AssetNumberRule::query()
            ->where('id', $data['asset_number_rule_id'])
            ->first();
        $asset_number_rule->setAttribute('class_name', $data['class_name']);
        $asset_number_rule->setAttribute('is_auto', $data['is_auto']);
        $asset_number_rule->save();
    }

    /**
     * 解除资产编号自动生成规则.
     */
    public static function resetAutoRule(string $class_name): void
    {
        AssetNumberRule::query()->where('class_name', $class_name)
            ->update(['class_name' => '无']);
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return AssetNumberRule::query()->pluck('name', 'id');
    }

    /**
     * 新增资产编号生成规则.
     */
    public function create(array $data): AssetNumberRule
    {
        $this->assetNumberRule->setAttribute('name', $data['name']);
        $this->assetNumberRule->setAttribute('formula', $data['formula']);
        $this->assetNumberRule->setAttribute('auto_increment_length', $data['auto_increment_length']);
        $this->assetNumberRule->save();

        return $this->assetNumberRule;
    }

    /**
     * 按照规则自动生成资产编号.
     */
    public function generate(): string
    {
        $formula = $this->assetNumberRule->getAttribute('formula');
        foreach ($this->formula() as $key => $value) {
            $formula = str_replace($key, $value, $formula);
        }

        return $formula;
    }

    /**
     * 资产编号生成规则定义.
     */
    protected function formula(): array
    {
        $auto_increment_length = $this->assetNumberRule->getAttribute('auto_increment_length');
        $auto_increment_count = $this->assetNumberRule->getAttribute('auto_increment_count') + 1;
        for ($i = strlen($auto_increment_count); $i < $auto_increment_length; $i++) {
            $auto_increment_count = '0'.$auto_increment_count;
        }

        return [
            '{year}' => Carbon::now()->year,
            '{month}' => Carbon::now()->month,
            '{day}' => Carbon::now()->day,
            '{auto-increment}' => $auto_increment_count,
        ];
    }

    /**
     * 规则自增计数+1.
     */
    public function addAutoIncrementCount(): void
    {
        $this->assetNumberRule->update([
            'auto_increment_count' => $this->assetNumberRule->getAttribute('auto_increment_count') + 1,
        ]);
    }
}
