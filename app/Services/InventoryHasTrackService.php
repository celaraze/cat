<?php

namespace App\Services;

use App\Models\InventoryHasTrack;
use JetBrains\PhpStorm\ArrayShape;

class InventoryHasTrackService
{
    public InventoryHasTrack $inventory_has_track;

    public function __construct(InventoryHasTrack $inventory_has_track = null)
    {
        $this->inventory_has_track = $inventory_has_track ?? new InventoryHasTrack();
    }

    /**
     * 盘点操作.
     */
    #[ArrayShape([
        'check' => 'string',
        'user_id' => 'int',
        'comment' => 'string',
    ])]
    public function check(array $data): InventoryHasTrack
    {
        $this->inventory_has_track->setAttribute('check', $data['check']);
        $this->inventory_has_track->setAttribute('user_id', auth()->id());
        $this->inventory_has_track->setAttribute('comment', $data['comment'] ?? '无');
        $this->inventory_has_track->save();

        return $this->inventory_has_track;
    }

    /**
     * 获取是否已经盘点过.
     */
    public function isChecked(): mixed
    {
        return $this->inventory_has_track->getAttribute('check');
    }
}
