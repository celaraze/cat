<?php

namespace App\Services;

use App\Models\Flow;
use App\Models\FlowHasNode;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FlowHasNodeService extends Service
{
    public function __construct(?FlowHasNode $flow_has_node = null)
    {
        $this->model = $flow_has_node ?? new FlowHasNode();
    }

    /**
     * @throws Exception
     */
    public function batchCreate(array $data): void
    {

        DB::beginTransaction();
        /* @var Flow $flow */
        $flow = DeviceService::getRetireFlow();
        if ($flow->service()->hasActiveForm()) {
            throw new Exception(__('flow.has_active_form'));
        }
        $flow->nodes()->delete();
        try {
            foreach ($data['nodes'] as $key => $node) {
                $data['order'] = $key;
                $data['role_id'] = $node;
                $this->model = new FlowHasNode();
                $this->create($data);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function delete(): ?bool
    {
        return $this->model->delete();
    }

    public function create(array $data): Model|FlowHasNode
    {
        $this->model = new FlowHasNode();
        $this->model->setAttribute('flow_id', $data['flow_id']);
        $this->model->setAttribute('order', $data['order']);
        $this->model->setAttribute('role_id', $data['role_id']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();

        return $this->model;
    }
}
