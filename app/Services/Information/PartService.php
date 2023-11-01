<?php

namespace App\Services\Information;

use App\Models\AssetNumberRule;
use App\Models\Information\Part;
use App\Services\AssetNumberRuleService;
use App\Services\AssetNumberTrackService;
use Exception;
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
     *
     * @return Collection
     */
    public static function pluckOptions(): Collection
    {
        return Part::query()->pluck('asset_number', 'id');
    }

    /**
     * 创建设备-配件关联.
     *
     * @param array $data
     * @return Model
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
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function create(array $data): void
    {
        // 开始事务
        DB::beginTransaction();
        try {
            $asset_number_rule = AssetNumberRule::query()
                ->where('class_name', $this->part::class)
                ->first()
                ->toArray();
            $asset_number_rule = new AssetNumberRule($asset_number_rule);
            $asset_number = $data['asset_number'];
            // 如果绑定了自动生成规则并且启用
            if ($asset_number_rule->getAttribute('is_auto')) {
                $asset_number_rule_service = new AssetNumberRuleService($asset_number_rule);
                $asset_number = $asset_number_rule_service->generate();
                $asset_number_rule_service->addAutoIncrementCount();
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
}
