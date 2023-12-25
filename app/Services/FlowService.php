<?php

namespace App\Services;

use App\Models\Flow;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FlowService extends Service
{
    public function __construct(Flow|string|null $flow_or_id = null)
    {
        if ($flow_or_id) {
            if (is_object($flow_or_id)) {
                $this->model = $flow_or_id;
            } else {
                $flow = Flow::query()
                    ->where('id', $flow_or_id)
                    ->first()
                    ->toArray();
                $this->model = new Flow($flow);
            }
        } else {
            $this->model = new Flow();
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
        $first_node = $this->model->nodes()
            ->where('parent_node_id', 0)
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

    /**
     * 删除节点.
     *
     * @throws Exception
     */
    public function delete(): void
    {
        if ($this->model->activeForms()) {
            throw new Exception(__('cat/flow_has_active_forms'));
        }
        try {
            DB::beginTransaction();
            $this->model->nodes()->delete();
            $this->model->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
