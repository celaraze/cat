<?php

namespace App\Services;

use App\Models\Flow;
use Illuminate\Support\Collection;

class FlowService
{
    public Flow $flow;

    public function __construct(Flow|string|null $flow_or_id = null)
    {
        if ($flow_or_id) {
            if (is_object($flow_or_id)) {
                $this->flow = $flow_or_id;
            } else {
                $flow = Flow::query()
                    ->where('id', $flow_or_id)
                    ->first()
                    ->toArray();
                $this->flow = new Flow($flow);
            }
        } else {
            $this->flow = new Flow();
        }
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return Flow::query()->pluck('name', 'id');
    }

    /**
     * 流程节点排序.
     */
    public function sortNodes(): array
    {
        $array['id'] = [];
        $array['name'] = [];
        $first_node = $this->flow->nodes->where('parent_node_id', 0)
            ->first();
        while (true) {
            if ($first_node) {
                $array['id'][] = $first_node->getAttribute('id');
                $array['name'][] = $first_node->getAttribute('name');
                $first_node = $first_node->childNode;
            } else {
                break;
            }
        }

        return $array;
    }
}
