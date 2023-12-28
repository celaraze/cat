<?php

namespace App\Services;

use App\Models\AssetNumberRule;
use App\Models\Device;
use App\Models\Flow;
use App\Models\Part;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class DeviceService extends Service
{
    public function __construct(?Model $device = null)
    {
        $this->model = $device ?? new Device();
    }

    public static function pluckOptions(string $key_column = 'id', array $exclude_ids = []): Collection
    {
        return Device::query()
            ->whereNotIn('id', $exclude_ids)
            ->whereNotIn('status', [3])
            ->get()
            ->mapWithKeys(function (Device $device) use ($key_column) {
                $title = '';
                $title .= $device->getAttribute('asset_number');
                $title .= ' | '.$device->getAttribute('name');
                $user = $device->users()->first();
                $user_name = $user?->getAttribute('name') ?? __('cat/device.no_user');
                $title .= ' | '.$user_name;

                return [$device->getAttribute($key_column) => $title];
            });
    }

    public static function isSetRetireFlow(): bool
    {
        /* @var Flow $flow */
        $flow = self::getRetireFlow();

        return $flow?->nodes()->count() ?? false;
    }

    public static function getRetireFlow(): Builder|Model|null
    {
        return Flow::query()
            ->where('slug', 'retire_flow')
            ->where('model_name', Device::class)
            ->first();
    }

    public function isExistHasUser(): bool
    {
        return $this->model->hasUsers()->count();
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'asset_number' => 'string',
        'category_id' => 'int',
        'name' => 'string',
        'brand_id' => 'int',
        'sn' => 'string',
        'specification' => 'string',
        'image' => 'string',
        'description' => 'string',
        'additional' => 'string',
        'creator_id' => 'int',
    ])]
    public function create(array $data): void
    {
        // 开始事务
        DB::beginTransaction();
        try {
            $asset_number = $data['asset_number'];
            $asset_number_rule = AssetNumberRule::query()
                ->where('class_name', $this->model::class)
                ->first();
            /* @var AssetNumberRule $asset_number_rule */
            if ($asset_number_rule) {
                // 如果绑定了自动生成规则并且启用
                if ($asset_number_rule->getAttribute('is_auto')) {
                    $asset_number_rule_service = new AssetNumberRuleService($asset_number_rule);
                    $asset_number = $asset_number_rule_service->generate();
                    $asset_number_rule_service->addAutoIncrementCount();
                }
            }
            $this->model->setAttribute('asset_number', $asset_number);
            $this->model->setAttribute('category_id', $data['category_id']);
            $this->model->setAttribute('name', $data['name']);
            $this->model->setAttribute('brand_id', $data['brand_id']);
            $this->model->setAttribute('sn', $data['sn']);
            $this->model->setAttribute('specification', $data['specification']);
            $this->model->setAttribute('image', $data['image']);
            $this->model->setAttribute('description', $data['description']);
            $this->model->setAttribute('additional', json_encode($data['additional']));
            $this->model->setAttribute('creator_id', $data['creator_id']);
            $this->model->save();
            $this->model->assetNumberTrack()
                ->create(['asset_number' => $asset_number]);
            // 写入事务
            DB::commit();
        } catch (Exception $exception) {
            // 回滚事务
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @throws Exception
     */
    public function retire(): void
    {
        try {
            DB::beginTransaction();
            $this->model->hasUsers()->delete();
            $this->model->hasParts()->delete();
            $this->model->hasSoftware()->delete();
            // 设备报废会携带所含配件全部报废
            foreach ($this->model->parts()->get() as $part) {
                /* @var Part $part */
                $part->setAttribute('status', 3);
                $part->save();
            }
            $this->model->setAttribute('status', 3);
            $this->model->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function isRetired(): bool
    {
        return $this->model->getAttribute('status') == 3;
    }
}
