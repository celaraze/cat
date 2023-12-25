<?php

namespace App\Services;

use App\Models\InventoryHasTrack;
use JetBrains\PhpStorm\ArrayShape;

class InventoryHasTrackService extends Service
{
    public function __construct(?InventoryHasTrack $inventory_has_track = null)
    {
        $this->model = $inventory_has_track ?? new InventoryHasTrack();
    }

    /**
     * 盘点操作.
     */
    #[ArrayShape([
        'check' => 'string',
        'creator_id' => 'int',
        'comment' => 'string',
    ])]
    public function check(array $data): InventoryHasTrack
    {
        $this->model->setAttribute('check', $data['check']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->setAttribute('comment', $data['comment']);
        $this->model->save();

        return $this->model;
    }

    /**
     * 获取是否已经盘点过.
     */
    public function isChecked(): mixed
    {
        return $this->model->getAttribute('check');
    }
}
