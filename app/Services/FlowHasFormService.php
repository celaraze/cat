<?php

namespace App\Services;

use App\Models\FlowHasForm;
use App\Models\FlowHasNode;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        $this->model->setAttribute('model_name', $data['model_name']);
        $this->model->setAttribute('model_id', $data['model_id']);
        $this->model->setAttribute('applicant_id', $data['applicant_id']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->setAttribute('comment', $data['comment']);
        $this->model->save();

        return $this->model;
    }

    /**
     * 流程结束为 true，流程未结束为 false.
     *
     * @throws Exception
     */
    public function process(array $data): bool
    {
        DB::beginTransaction();
        try {
            $this->model->setAttribute('approver_id', $data['approver_id']);
            $this->model->setAttribute('creator_id', $data['creator_id']);
            $this->model->setAttribute('comment', $data['comment']);
            $this->model->setAttribute('status', $data['status']);
            $this->model->save();
            /* @var FlowHasNode $flow_has_node */
            $flow_has_node = $this->model->node()->first();
            switch ($data['status']) {
                // 如果审批同意
                case 1:
                    if ($flow_has_node->next()) {
                        $new_model = $this->model->replicate();
                        $new_model->setAttribute('flow_has_node_id', $flow_has_node->next()->getKey());
                        $new_model->setAttribute('approver_id', 0);
                        $new_model->setAttribute('comment', null);
                        $new_model->setAttribute('status', 0);
                        $new_model->save();
                        DB::commit();

                        return false;
                    } else {
                        $this->model->setAttribute('status', 4);
                        $this->model->save();
                        // 正常审批流程结束
                        // 资产废弃钩子
                        if ($this->model->node->flow->slug == 'retire_flow') {
                            /* @var Model $model_name */
                            $model_name = $this->model->getAttribute('model_name');
                            $model = $model_name::query()->where('id', $this->model->getAttribute('model_id'))->first();
                            $model->service()->retire();
                        }
                        DB::commit();

                        return true;
                    }
                    // 如果审批退回
                case 2:
                    if ($flow_has_node->previous()) {
                        $new_model = $this->model->replicate();
                        $new_model->setAttribute('flow_has_node_id', $flow_has_node->previous()->getKey());
                        $new_model->setAttribute('approver_id', 0);
                        $new_model->setAttribute('comment', null);
                        $new_model->setAttribute('status', 0);
                        $new_model->save();
                        DB::commit();

                        return false;
                    } else {
                        DB::rollBack();
                        throw new Exception('表单已经在最初节点');
                    }
                    // 如果审批驳回
                case 3:
                    DB::commit();

                    return true;
                default:
                    DB::rollBack();
                    throw new Exception('审批状态错误');
            }
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    // 两种情况可以认定是完成了
    // 一是所有的表单记录中，有状态为 3 即驳回
    // 二是所有的表单记录中，没有剩余的表单节点并且满足条件一
    public function isCompleted(): bool
    {
        $flow_has_forms = $this->model->forms();
        if ($flow_has_forms->where('status', 3)->count()) {
            return true;
        }

        if (! $this->model->node->next() && $this->model->getAttribute('status')) {
            return true;
        }

        return false;
    }

    public function isProcessed(): bool
    {
        return $this->model->getAttribute('status');
    }
}
