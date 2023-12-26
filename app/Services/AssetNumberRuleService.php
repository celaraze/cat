<?php

namespace App\Services;

use App\Models\AssetNumberRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class AssetNumberRuleService extends Service
{
    public function __construct(?AssetNumberRule $asset_number_rule = null)
    {
        $this->model = $asset_number_rule ?? new AssetNumberRule();
    }

    public static function isAuto(string $class_name): mixed
    {
        return AssetNumberRule::query()
            ->where('class_name', $class_name)
            ->value('is_auto');
    }

    public static function getAutoRule(string $class_name): Model|null|Builder
    {
        return AssetNumberRule::query()
            ->where('class_name', $class_name)
            ->first();
    }

    #[ArrayShape(['class_name' => 'string', 'is_auto' => 'bool'])]
    public static function setAutoRule(array $data): bool
    {
        $asset_number_rule = AssetNumberRule::query()
            ->where('id', $data['asset_number_rule_id'])
            ->first();
        $asset_number_rule->setAttribute('class_name', $data['class_name']);
        $asset_number_rule->setAttribute('is_auto', $data['is_auto']);

        return $asset_number_rule->save();
    }

    public static function resetAutoRule(string $class_name): int
    {
        return AssetNumberRule::query()
            ->where('class_name', $class_name)
            ->update(['class_name' => null]);
    }

    public static function pluckOptions(): Collection
    {
        return AssetNumberRule::query()->pluck('name', 'id');
    }

    #[ArrayShape([
        'name' => 'string',
        'formula' => 'string',
        'auto_increment_length' => 'int',
        'creator_id' => 'int',
    ])]
    public function create(array $data): AssetNumberRule
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('formula', $data['formula']);
        $this->model->setAttribute('auto_increment_length', $data['auto_increment_length']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();

        return $this->model;
    }

    public function delete(): ?bool
    {
        return $this->model->delete();
    }

    public function generate(): string
    {
        $formula = $this->model->getAttribute('formula');
        foreach ($this->formula() as $key => $value) {
            $formula = str_replace($key, $value, $formula);
        }

        return $formula;
    }

    protected function formula(): array
    {
        $auto_increment_length = $this->model->getAttribute('auto_increment_length');
        $auto_increment_count = $this->model->getAttribute('auto_increment_count') + 1;
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

    public function addAutoIncrementCount(): void
    {
        $auto_increment_count = (int) $this->model->getAttribute('auto_increment_count');
        $this->model->setAttribute('auto_increment_count', $auto_increment_count + 1);
        $this->model->save();
    }
}
