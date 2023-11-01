<?php

namespace App\Services;

use App\Models\InventoryHasTrack;

class InventoryHasTrackService
{
    public InventoryHasTrack $inventory_has_track;

    public function __construct(InventoryHasTrack $inventory_has_track = null)
    {
        if ($inventory_has_track) {
            $this->inventory_has_track = $inventory_has_track;
        } else {
            $this->inventory_has_track = new InventoryHasTrack();
        }
    }

    /**
     * 盘点操作.
     *
     * @param array $data
     * @return InventoryHasTrack
     */
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
     *
     * @return mixed
     */
    public function isChecked(): mixed
    {
        return $this->inventory_has_track->getAttribute('check');
    }
}
