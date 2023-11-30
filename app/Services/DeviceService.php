<?php

namespace App\Services;

use App\Models\AssetNumberRule;
use App\Models\Device;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DeviceService
{
    public Model $device;

    public function __construct(Model $device = null)
    {
        if (!$device) {
            $this->device = new Device();
        } else {
            $this->device = $device;
        }
    }

    /**
     * 选单.
     *
     * @return Collection
     */
    public static function pluckOptions(): Collection
    {
        return Device::query()->pluck('asset_number', 'id');
    }

    /**
     * 判断设备分配记录是否存在.
     *
     * @return bool
     */
    public function isExistHasUser(): bool
    {
        return $this->device->hasUsers()->count();
    }

    /**
     * 创建设备-用户记录.
     *
     * @param array $data
     * @return Model
     */
    public function createHasUser(array $data): Model
    {
        return $this->device->hasUsers()->create($data);
    }

    /**
     * 新增设备.
     *
     * @param array $data
     * @param bool $is_auto_asset_number
     * @return void
     * @throws Exception
     */
    public function create(array $data, bool $is_auto_asset_number = false): void
    {
        // 开始事务
        DB::beginTransaction();
        try {
            $asset_number_rule = AssetNumberRule::query()
                ->where('class_name', $this->device::class)
                ->first()
                ->toArray();
            $asset_number_rule = new AssetNumberRule($asset_number_rule);
            $asset_number = $data['asset_number'];
            // 如果绑定了自动生成规则并且启用
            if ($is_auto_asset_number && $asset_number_rule->getAttribute('is_auto')) {
                $asset_number_rule_service = new AssetNumberRuleService($asset_number_rule);
                $asset_number = $asset_number_rule_service->generate();
                $asset_number_rule_service->addAutoIncrementCount();
            }
            AssetNumberTrackService::create($asset_number);
            $this->device->setAttribute('asset_number', $asset_number);
            $this->device->setAttribute('category_id', $data['category_id']);
            $this->device->setAttribute('name', $data['name']);
            $this->device->setAttribute('brand_id', $data['brand_id']);
            $this->device->setAttribute('sn', $data['sn'] ?? '无');
            $this->device->setAttribute('specification', $data['specification'] ?? '无');
            $this->device->setAttribute('image', $data['image'] ?? '无');
            $this->device->save();
            // 写入事务
            DB::commit();
        } catch (Exception $exception) {
            // 回滚事务
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 创建设备-配件记录.
     *
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function createHasPart(array $data): Model
    {
        if ($this->device->hasParts()->where('part_id', $data['part_id'])->count()) {
            throw new Exception('配件已经附加到此设备');
        }
        return $this->device->hasParts()->create($data);
    }

    /**
     * 创建设备-软件记录.
     *
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function createHasSoftware(array $data): Model
    {
        if ($this->device->hasSoftware()->where('software_id', $data['software_id'])->count()) {
            throw new Exception('软件已经附加到此设备');
        }
        return $this->device->hasSoftware()->create($data);
    }

    /**
     * 删除设备-用户记录.
     *
     * @param array $data
     * @return int
     */
    public function deleteHasUser(array $data): int
    {
        $this->device->hasUsers()->first()->update(['delete_comment' => $data['delete_comment']]);
        return $this->device->hasUsers()->delete();
    }
}
