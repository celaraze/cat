<?php

namespace App\Services;

use App\Models\AssetNumberRule;
use App\Models\Flow;
use App\Models\Software;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class SoftwareService extends Service
{
    public function __construct(?Software $software = null)
    {
        $this->model = $software ?? new Software();
    }

    public static function pluckOptions(): Collection
    {
        return Software::query()
            ->whereNotIn('status', [3])
            ->get()
            ->mapWithKeys(function (Software $software) {
                $title = '';
                $title .= $software->getAttribute('asset_number');
                $title .= ' | '.$software->brand()->first()?->getAttribute('name') ?? ' | '.__('cat/software.unknown_brand');
                $title .= ' | '.$software->getAttribute('name');
                $title .= ' | '.$software->getAttribute('specification');
                $title .= ' | '.$software->category()->first()?->getAttribute('name') ?? ' | '.__('cat/software.unknown_category');
                if ($software->getAttribute('max_license_count') == 0) {
                    $title .= ' - 无限制';
                } else {
                    if ($software->getAttribute('max_license_count') > $software->usedCount()) {
                        $title .= ' | '.$software->usedCount().'/'.$software->getAttribute('max_license_count').__('cat/used');
                    } else {
                        $title .= ' | '.$software->usedCount().'/'.$software->getAttribute('max_license_count').__('cat/used').' | '.__('cat/software.license_exhausted');
                    }
                }

                return [$software->getKey() => $title];
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
            ->where('model_name', Software::class)
            ->first();
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'asset_number' => 'string',
        'name' => 'string',
        'category_id' => 'int',
        'brand_id' => 'int',
        'sn' => 'string',
        'specification' => 'string',
        'image' => 'string',
        'max_license_count' => 'int',
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
                ->where('class_name', Software::class)
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
            $this->model->setAttribute('name', $data['name']);
            $this->model->setAttribute('category_id', $data['category_id']);
            $this->model->setAttribute('brand_id', $data['brand_id']);
            $this->model->setAttribute('sn', $data['sn']);
            $this->model->setAttribute('specification', $data['specification']);
            $this->model->setAttribute('image', $data['image']);
            $this->model->setAttribute('max_license_count', $data['max_license_count']);
            $this->model->setAttribute('description', $data['description']);
            $this->model->setAttribute('additional', json_encode($data['additional']));
            $this->model->setAttribute('status', 4);
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
            $this->model->hasSoftware()->delete();
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

    public function isRetiring()
    {
        return $this->model->getAttribute('isRetiring');
    }
}
