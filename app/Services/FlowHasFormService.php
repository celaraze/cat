<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Flow;
use App\Models\FlowHasForm;
use App\Models\FlowHasNode;
use App\Models\Part;
use App\Models\Setting;
use App\Models\Software;
use App\Traits\Services\HasFootprint;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Ramsey\Uuid\Uuid;

class FlowHasFormService
{
    use HasFootprint;

    public FlowHasForm $model;

    public function __construct(?FlowHasForm $flow_has_form = null)
    {
        $this->model = $flow_has_form ?? new FlowHasForm();
    }

    /**
     * 流程表单审批.
     * todo 这个方法写的一坨屎，先能用，后面重构.
     *
     * @throws Exception
     */
    public function approve(int $status, string $approve_comment): void
    {
        // 开始数据库事务
        DB::beginTransaction();
        // 先判断表单状态是否是已驳回状态
        if ($this->model->getAttribute('status') == 3) {
            throw new Exception(__('cat/flow_has_form_has_been_rejected'));
        }
        // 无论如何，生成一条新记录，同时表单顺序计数+1，然后删除旧记录
        $new_form = $this->model->replicate();
        $new_form->setAttribute('stage', $this->model->getAttribute('stage') + 1);
        $this->model->delete();
        $next_node = null;
        // 如果审批是同意
        if ($status == 1) {
            // 如果这是新表单，从第一个节点开始走流程，也就是 parent_node_id == 0
            // 否则执行流程节点顺序
            if (! $this->model->getAttribute('node_id')) {
                $parent_node_id = 0;
            } else {
                $parent_node_id = $this->model->getAttribute('node_id');
            }
            $next_node = FlowHasNode::query()
                ->where('flow_id', $this->model->getAttribute('flow_id'))
                ->where('parent_node_id', $parent_node_id)
                ->first();
            $next_next_node = $next_node->childNode;
            // 判断下一个节点是不是最终节点，就是判断下一个节点的下一个节点是否存在
            if (! $next_next_node) {
                $status = 4;
            }
        }
        // 如果审批是退回
        if ($status == 2) {
            if (! $this->model->getAttribute('node_id')) {
                // 数据库事务回滚
                DB::rollBack();
                throw new Exception(__('cat/flow_has_form_in_start'));
            }
            $current_node = FlowHasNode::query()
                ->where('id', $this->model->getAttribute('node_id'))
                ->first();
            /* @var FlowHasNodeService $prev_node 这里 $next_node 实际上是 $prev_node */
            $next_node = $current_node->parentNode;
            // 判断表单是否已经被退回到了第一个节点，即 $next_node 为空
            if (! $next_node) {
                // 数据库事务回滚
                DB::rollBack();
                throw new Exception(__('cat/flow_has_form_can_not_be_rejected'));
            }
        }
        // 排除流程已经结束的表单，即通过和驳回的
        // 审批完成就是没有已下一个节点里，即 $next_node 为空
        // 同时要排除表单不是被驳回的，则是判断 $status != 3
        // 再次排除表单已经结案的，则是判断 $status != 4
        if (! $next_node && $status != 3 && $status != 4) {
            // 数据库事务回滚
            DB::rollBack();
            throw new Exception(__('cat/flow_has_form_has_been_finished'));
        }
        $new_form->setAttribute('approve_user_id', auth()->id());
        $new_form->setAttribute('approve_user_name', auth()->user()->name);
        if ($status == 1 || $status == 2 || $status == 4) {
            $new_form->setAttribute('current_approve_user_id', $next_node->getAttribute('user_id'));
            $new_form->setAttribute('current_approve_role_id', $next_node->getAttribute('role_id'));
            $new_form->setAttribute('node_id', $next_node->getKey());
        }
        // PATCH 表单退回到最初的申请人关卡时，当前审批人和审核角色都只能从节点信息读到0，需要做处理将当前审批人改为申请人
        if (! $new_form->getAttribute('current_approve_user_id') && ! $new_form->getAttribute('current_approve_role_id')) {
            $new_form->setAttribute('current_approve_user_id', $new_form->getAttribute('applicant_user_id'));
        }
        $new_form->setAttribute('status', $status);
        $new_form->setAttribute('approve_comment', $approve_comment);
        // 如果表单流程结束，将经历的节点信息快照方式保存
        if ($status == 3 || $status == 4) {
            /* @var Flow $flow */
            $flow = $this->model->flow()->first();

            $flow_progress = $flow->service()->sortNodes();
            $new_form->setAttribute('flow_progress', json_encode($flow_progress));

            // 表单完成钩子
            if ($status == 4) {
                // 报废流程
                // 设备
                $device_delete_flow_id = Setting::query()
                    ->where('custom_key', 'device_retire_flow_id')
                    ->value('custom_value');
                if ($device_delete_flow_id == $flow->getKey()) {
                    /* @var Device $device */
                    $device = Device::query()
                        ->where('asset_number', $this->model->getAttribute('payload'))
                        ->first();
                    if (! $device) {
                        throw new Exception(__('cat/flow_has_form_payload_device_not_found'));
                    }
                    $device->service()->retire();
                }
                // 配件
                $part_delete_flow_id = Setting::query()
                    ->where('custom_key', 'part_retire_flow_id')
                    ->value('custom_value');
                if ($part_delete_flow_id == $flow->getKey()) {
                    /* @var Part $part */
                    $part = Part::query()
                        ->where('asset_number', $this->model->getAttribute('payload'))
                        ->first();
                    if (! $part) {
                        throw new Exception(__('cat/flow_has_form_payload_part_not_found'));
                    }
                    $part->service()->retire();
                }
                // 软件
                $software_delete_flow_id = Setting::query()
                    ->where('custom_key', 'software_retire_flow_id')
                    ->value('custom_value');
                if ($software_delete_flow_id == $flow->getKey()) {
                    /* @var  Software $software */
                    $software = Software::query()
                        ->where('asset_number', $this->model->getAttribute('payload'))
                        ->first();
                    if (! $software) {
                        throw new Exception(__('cat/flow_has_form_payload_software_not_found'));
                    }
                    $software->service()->retire();
                }
            }
        }
        $new_form->save();
        // 数据库事务提交
        DB::commit();
        redirect('/flow-has-forms/'.$new_form->getKey());
    }

    /**
     * 根据不同情况获取节点顺序信息.
     * 结案的表单从持久化数据中获取，没结案的实时获取.
     */
    public function getNodes(): mixed
    {
        $status = $this->model->getAttribute('status');
        if ($status == 3 || $status == 4) {
            $nodes = json_decode($this->model->getAttribute('flow_progress'), true);
        } else {
            /* @var Flow $flow */
            $flow = $this->model->flow()->first();
            $nodes = $flow->service()->sortNodes();
        }
        $key = array_search($this->model->getAttribute('node_id'), $nodes['id']);
        $nodes['name'][$key] = '🚩'.$nodes['name'][$key];

        return $nodes;
    }

    /**
     * 通过form_id获取FlowHasForm模型并赋值给当前类.
     */
    public function setFlowHasFormById(string $flow_has_form_id): void
    {
        /* @var FlowHasForm $flow_has_form */
        $flow_has_form = FlowHasForm::query()->where('id', $flow_has_form_id)->first();
        $this->model = $flow_has_form;

    }

    /**
     * 创建流程表单.
     *
     * @throws Exception
     */
    #[ArrayShape([
        'name' => 'string',
        'flow_id' => 'int',
        'comment' => 'string',
        'payload' => '?string',
    ])]
    public function create(array $data): bool
    {
        /* @var Flow $flow */
        $flow = Flow::query()->where('id', $data['flow_id'])->first();
        $node_counts = $flow->nodes()
            ->where('parent_node_id', '!=', 0)
            ->count();
        if (! $node_counts) {
            throw new Exception(__('cat/flow_has_form_need_more_nodes'));
        }
        $first_node = $flow->nodes()
            ->where('parent_node_id', 0)
            ->first();
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('flow_name', $flow->getAttribute('name'));
        $this->model->setAttribute('uuid', Uuid::uuid4());
        $this->model->setAttribute('flow_id', $flow->getKey());
        $this->model->setAttribute('applicant_user_id', auth()->id());
        $this->model->setAttribute('current_approve_user_id', $this->model->getAttribute('applicant_user_id'));
        $this->model->setAttribute('comment', $data['comment']);
        $this->model->setAttribute('node_id', $first_node->getKey());
        $this->model->setAttribute('node_name', $first_node->getAttribute('name'));
        if (isset($data['payload'])) {
            $this->model->setAttribute('payload', $data['payload']);
        }

        return $this->model->save();
    }

    /**
     * 表单所属流程是否存在.
     */
    public function isExistFlow(): int
    {
        return $this->model->flow()->count();
    }

    /**
     * 表单是否结案.
     */
    public function isFinished(): bool
    {
        $status = $this->model->getAttribute('status');
        if ($status == 3 or $status == 4) {
            return true;
        }

        return false;
    }
}
