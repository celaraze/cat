<?php

namespace App\Services;

use App\Models\AssetNumberRule;
use App\Models\Flow;
use App\Models\Part;
use App\Models\Setting;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PartService
{
    public Part $part;

    public function __construct(Part $part = null)
    {
        if ($part) {
            $this->part = $part;
        } else {
            $this->part = new Part();
        }
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return Part::query()->pluck('asset_number', 'id');
    }

    /**
     * 判断是否配置报废流程.
     */
    public static function isSetRetireFlow(): bool
    {
        return Setting::query()
            ->where('custom_key', 'part_retire_flow_id')
            ->count();

    }

    /**
     * 创建设备-配件关联.
     *
     * @throws Exception
     */
    public function createHasPart(array $data): Model
    {
        if ($this->part->hasParts()->where('device_id', $data['device_id'])->count()) {
            throw new Exception('配件已经附加到此设备');
        }

        return $this->part->hasParts()->create($data);
    }

    /**
     * 新增配件.
     *
     * @throws Exception
     */
    public function create(array $data): void
    {
        // 开始事务
        DB::beginTransaction();
        try {
            $asset_number = $data['asset_number'];
            $asset_number_rule = AssetNumberRule::query()
                ->where('class_name', $this->part::class)
                ->first();
            if ($asset_number_rule) {
                $asset_number_rule = new AssetNumberRule($asset_number_rule->toArray());
                // 如果绑定了自动生成规则并且启用
                if ($asset_number_rule->getAttribute('is_auto')) {
                    $asset_number_rule_service = new AssetNumberRuleService($asset_number_rule);
                    $asset_number = $asset_number_rule_service->generate();
                    $asset_number_rule_service->addAutoIncrementCount();
                }
            }
            AssetNumberTrackService::create($asset_number);
            $this->part->setAttribute('asset_number', $asset_number);
            $this->part->setAttribute('category_id', $data['category_id']);
            $this->part->setAttribute('brand_id', $data['brand_id']);
            $this->part->setAttribute('sn', $data['sn'] ?? '无');
            $this->part->setAttribute('specification', $data['specification'] ?? '无');
            $this->part->setAttribute('image', $data['image'] ?? '无');
            $this->part->save();
            // 写入事务
            DB::commit();
        } catch (Exception $exception) {
            // 回滚事务
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 配件报废.
     *
     * @throws Exception
     */
    public function retire(): void
    {
        try {
            DB::beginTransaction();
            $this->part->hasParts()->delete();
            $this->part->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 获取已配置的配件报废流程.
     *
     * @throws Exception
     */
    public function getRetireFlow(): Builder|Model
    {
        $flow_id = Setting::query()
            ->where('custom_key', 'part_retire_flow_id')
            ->value('custom_value');
        if (! $flow_id) {
            throw new Exception('还未配置配件报废流程');
        }
        $flow = Flow::query()
            ->where('id', $flow_id)
            ->first();
        if (! $flow) {
            throw new Exception('未找到已配置的配件报废流程');
        }

        return $flow;
    }
}
