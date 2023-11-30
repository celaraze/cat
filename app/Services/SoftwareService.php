<?php

namespace App\Services;

use App\Models\AssetNumberRule;
use App\Models\Software;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SoftwareService
{
    public Software $software;

    public function __construct(Software $software = null)
    {
        if ($software) {
            return $this->software = $software;
        } else {
            return $this->software = new Software();
        }
    }

    /**
     * 选单.
     *
     * @return Collection
     */
    public static function pluckOptions(): Collection
    {
        return Software::query()->pluck('name', 'id');
    }

    /**
     * 创建设备-配件关联.
     *
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function createHasSoftware(array $data): Model
    {
        if ($this->software->hasSoftware()->where('device_id', $data['device_id'])->count()) {
            throw new Exception('软件已经附加到此设备');
        }
        return $this->software->hasSoftware()->create($data);
    }

    /**
     * 新增软件.
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
                ->where('class_name', Software::class)
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
            $this->software->setAttribute('asset_number', $asset_number);
            $this->software->setAttribute('name', $data['name']);
            $this->software->setAttribute('category_id', $data['category_id']);
            $this->software->setAttribute('brand_id', $data['brand_id']);
            $this->software->setAttribute('sn', $data['sn'] ?? '无');
            $this->software->setAttribute('specification', $data['specification'] ?? '无');
            $this->software->setAttribute('image', $data['image'] ?? '无');
            $this->software->setAttribute('max_license_count', $data['max_license_count']);
            $this->software->save();
            // 写入事务
            DB::commit();
        } catch (Exception $exception) {
            // 回滚事务
            DB::rollBack();
            throw $exception;
        }
    }
}
