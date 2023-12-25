<?php

namespace App\Models;

use App\Services\ConsumableHasTrackService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsumableHasTrack extends Model
{
    use HasFactory, SoftDeletes;

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function consumable(): BelongsTo
    {
        return $this->belongsTo(Consumable::class, 'consumable_id', 'id');
    }

    public function service(): ConsumableHasTrackService
    {
        return new ConsumableHasTrackService($this);
    }
}
