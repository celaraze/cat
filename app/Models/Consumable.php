<?php

namespace App\Models;

use App\Services\ConsumableService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consumable extends Model
{
    use HasFactory, SoftDeletes;

    public function category(): BelongsTo
    {
        return $this->belongsTo(ConsumableCategory::class, 'category_id', 'id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ConsumableUnit::class, 'unit_id', 'id');
    }

    public function quantity(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $this->tracks()->sum('quantity'),
        );
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(ConsumableHasTrack::class, 'consumable_id', 'id');
    }

    public function service(): ConsumableService
    {
        return new ConsumableService($this);
    }

    /**
     * 访问器，额外信息.
     */
    public function additional(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => json_decode($value, true),
        );
    }

    public function forms(): HasMany
    {
        return $this->hasMany(FlowHasForm::class, 'model_id', 'id')
            ->where('model_name', self::class);
    }
}
