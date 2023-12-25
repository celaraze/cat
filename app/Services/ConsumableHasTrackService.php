<?php

namespace App\Services;

use App\Models\Consumable;
use App\Models\ConsumableHasTrack;
use Exception;

class ConsumableHasTrackService extends Service
{
    public function __construct(?ConsumableHasTrack $consumable_has_track = null)
    {
        $this->model = $consumable_has_track ?? new ConsumableHasTrack();
    }

    /**
     * @throws Exception
     */
    public function create(array $data): ConsumableHasTrack
    {
        $consumable = Consumable::query()->where('id', $data['consumable_id'])->first();
        if (! $consumable) {
            throw new Exception('cat/consumable_has_track.consumable_not_found');
        }
        // 如果领用数量超出库存数量就抛出异常
        if ($data['quantity'] < 0 && abs($data['quantity']) > $consumable->getAttribute('quantity')) {
            throw new Exception('cat/consumable_has_track.quantity_invalid');
        }
        $this->model->setAttribute('consumable_id', $data['consumable_id']);
        $this->model->setAttribute('quantity', $data['quantity']);
        $this->model->setAttribute('comment', $data['comment']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();

        return $this->model;
    }
}
