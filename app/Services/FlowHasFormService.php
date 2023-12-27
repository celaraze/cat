<?php

namespace App\Services;

use App\Models\Flow;
use App\Models\FlowHasForm;
use Exception;
use Ramsey\Uuid\Uuid;

class FlowHasFormService extends Service
{
    public function __construct(?FlowHasForm $flow_has_form = null)
    {
        $this->model = $flow_has_form ?? new FlowHasForm();
    }

    public function create(array $data): FlowHasForm
    {
        $this->model->setAttribute('uuid', Uuid::uuid4());
        $this->model->setAttribute('flow_has_node_id', $data['flow_has_node_id']);
        $this->model->setAttribute('model_class', $data['model_class']);
        $this->model->setAttribute('model_id', $data['model_id']);
        $this->model->setAttribute('applicant_id', $data['applicant_id']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->setAttribute('comment', $data['comment']);
        $this->model->save();

        return $this->model;
    }

    /**
     * @throws Exception
     */
    public function approve(array $data): bool
    {
        $this->model->setAttribute('approver_id', $data['approver_id']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->setAttribute('comment', $data['comment']);
        $this->model->setAttribute('status', $data['status']);
        $this->model->save();
        if ($this->model->node->next()) {
            $new_model = $this->model->replicate();
            $new_model->setAttribute('flow_has_node_id', $data['flow_has_node_id']);
            $new_model->setAttribute('approver_id', null);
            $new_model->setAttribute('comment', null);
            $new_model->save();

            return false;
        } else {
            return true;
        }
    }

    // 两种情况可以认定是完成了
    // 一是所有的表单记录中，有状态为 3 即驳回
    // 二十所有的表单记录中，没有剩余的表单节点并且满足条件一
    public function isCompleted(): bool
    {
        $flow_has_forms = Flow::query()
            ->where('slug', 'device_retire_flow')
            ->first()
            ->forms
            ->where('model_id', $this->model->getAttribute('model_id'));
        if ($flow_has_forms->where('status', 3)->count()) {
            return true;
        } else {
            if ($flow_has_forms->node->next()) {
                return false;
            } else {
                return true;
            }
        }
    }
}
