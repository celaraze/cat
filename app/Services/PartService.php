<?php

namespace App\Services;

use App\Models\AssetNumberRule;
use App\Models\Flow;
use App\Models\Part;
use App\Models\Setting;
use App\Traits\Services\HasFootprint;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class PartService
{
    use HasFootprint;

    public Part $model;

    public function __construct(?Part $part = null)
    {
        $this->model = $part ?? new Part();
    }

    /**
     * 选单.
     */
    public static function pluckOptions(string $key_column = 'id', array $exclude_ids = []): Collection
    {
        return Part::query()
            ->whereNotIn($key_column, $exclude_ids)
            ->whereNotIn('status', [3])
            ->get()
            ->mapWithKeys(function (Part $part) {
                $title = '';
                $title .= $part->getAttribute('asset_number');
                $title .= ' | '.$part->brand()->first()?->getAttribute('name') ?? __('cat/unknown_brand');
                $title .= ' | '.$part->getAttribute('specification');
                $title .= ' | '.$part->category()->first()?->getAttribute('name') ?? __('cat/unknown_category');

                return [$part->getKey() => $title];
            });
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
     * 新增配件.
     *
     * @throws Exception
     */
    #[ArrayShape([
        'asset_number' => 'string',
        'category_id' => 'int',
        'brand_id' => 'int',
        'sn' => 'string',
        'specification' => 'string',
        'image' => 'string',
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
            $this->model->setAttribute('brand_id', $data['brand_id']);
            $this->model->setAttribute('sn', $data['sn']);
            $this->model->setAttribute('specification', $data['specification']);
            $this->model->setAttribute('image', $data['image']);
            $this->model->setAttribute('description', $data['description']);
            $this->model->setAttribute('additional', json_encode($data['additional']));
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
     * 配件报废.
     *
     * @throws Exception
     */
    public function retire(): void
    {
        try {
            DB::beginTransaction();
            $this->model->hasParts()->delete();
            $this->model->setAttribute('status', 3);
            $this->model->save();
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
            throw new Exception(__('cat/part_retire_flow_not_set'));
        }
        $flow = Flow::query()
            ->where('id', $flow_id)
            ->first();
        if (! $flow) {
            throw new Exception(__('cat/part_retire_flow_not_found'));
        }

        return $flow;
    }

    /**
     * 是否报废.
     */
    public function isRetired(): bool
    {
        if ($this->model->getAttribute('status') == 3) {
            return true;
        } else {
            return false;
        }
    }

    public function setPartById(int $part_id): void
    {
        /* @var Part $part */
        $part = Part::query()->where('id', $part_id)->first();
        $this->model = $part;
    }
}
