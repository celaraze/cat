<?php

namespace App\Services;

use App\Models\FlowHasForm;
use App\Models\FlowHasNode;
use Exception;
use Illuminate\Support\Facades\DB;

class FlowHasFormService
{
    public FlowHasForm $flow_has_form;

    public function __construct(FlowHasForm $flow_has_form = null)
    {
        if ($flow_has_form) {
            $this->flow_has_form = $flow_has_form;
        } else {
            $this->flow_has_form = new FlowHasForm();
        }

    }

    /**
     * 流程表单审批.
     *
     * @param int $status
     * @param string $approve_comment
     * @return void
     * @throws Exception
     */
    public function approve(int $status, string $approve_comment): void
    {
        // 开始数据库事务
        DB::beginTransaction();
        // 先判断表单状态是否是已驳回状态
        if ($this->flow_has_form->getAttribute('status') == 3) {
            throw new Exception('表单已经被驳回，请重新提交申请');
        }
        // 无论如何，生成一条新记录，同时表单顺序计数+1，然后删除旧记录
        $new_form = $this->flow_has_form->replicate();
        $new_form->setAttribute('stage', $this->flow_has_form->getAttribute('stage') + 1);
        $this->flow_has_form->delete();
        $next_node = null;
        // 如果审批是同意
        if ($status == 1) {
            // 如果这是新表单，从第一个节点开始走流程，也就是 parent_node_id == 0
            // 否则执行流程节点顺序
            if (!$this->flow_has_form->getAttribute('node_id')) {
                $parent_node_id = 0;
            } else {
                $parent_node_id = $this->flow_has_form->getAttribute('node_id');
            }
            $next_node = FlowHasNode::query()
                ->where('flow_id', $this->flow_has_form->getAttribute('flow_id'))
                ->where('parent_node_id', $parent_node_id)
                ->first();
            $next_next_node = $next_node->childNode;
            // 判断下一个节点是不是最终节点，就是判断下一个节点的下一个节点是否存在
            if (!$next_next_node) {
                $status = 4;
            }
        }
        // 如果审批是退回
        if ($status == 2) {
            if (!$this->flow_has_form->getAttribute('node_id')) {
                // 数据库事务回滚
                DB::rollBack();
                throw new Exception('表单已在最初始阶段，无法退回');
            }
            $current_node = FlowHasNode::query()
                ->where('id', $this->flow_has_form->getAttribute('node_id'))
                ->first();
            /* @var FlowHasNodeService $prev_node 这里 $next_node 实际上是 $prev_node */
            $next_node = $current_node->parentNode;
            // 判断表单是否已经被退回到了第一个节点，即 $next_node 为空
            if (!$next_node) {
                // 数据库事务回滚
                DB::rollBack();
                throw new Exception('流程无法退回，请选择驳回申请');
            }
        }
        // 排除流程已经结束的表单，即通过和驳回的
        // 审批完成就是没有已下一个节点里，即 $next_node 为空
        // 同时要排除表单不是被驳回的，则是判断 $status != 3
        // 再次排除表单已经结案的，则是判断 $status != 4
        if (!$next_node && $status != 3 && $status != 4) {
            // 数据库事务回滚
            DB::rollBack();
            throw new Exception('流程已经终结');
        }
        $new_form->setAttribute('approve_user_id', auth()->id());
        $new_form->setAttribute('approve_user_name', auth()->user()->name);
        if ($status == 1 || $status == 2 || $status == 4) {
            $new_form->setAttribute('current_approve_user_id', $next_node->getAttribute('user_id'));
            $new_form->setAttribute('current_approve_role_id', $next_node->getAttribute('role_id'));
            $new_form->setAttribute('node_id', $next_node->getKey());
        }
        # PATCH 表单退回到最初的申请人关卡时，当前审批人和审核角色都只能从节点信息读到0，需要做处理将当前审批人改为申请人
        if (!$new_form->getAttribute('current_approve_user_id') && !$new_form->getAttribute('current_approve_role_id')) {
            $new_form->setAttribute('current_approve_user_id', $new_form->getAttribute('applicant_user_id'));
        }
        $new_form->setAttribute('status', $status);
        $new_form->setAttribute('approve_comment', $approve_comment);
        // 如果表单流程结束，将经历的节点信息快照方式保存
        if ($status == 3 || $status == 4) {
            $flow_progress = $this->flow_has_form->flow->service()->sortNodes();
            $new_form->setAttribute('flow_progress', json_encode($flow_progress));
        }
        $new_form->save();
        // 数据库事务提交
        DB::commit();
        redirect('/flow-has-forms/' . $new_form->getKey());
    }

    /**
     * 根据不同情况获取节点顺序信息.
     * 结案的表单从持久化数据中获取，没结案的实时获取.
     *
     * @return mixed
     */
    public function sortNodes(): mixed
    {
        $status = $this->flow_has_form->getAttribute('status');
        if ($status == 3 || $status == 4) {
            $nodes = json_decode($this->flow_has_form->getAttribute('flow_progress'), true);
        } else {
            $nodes = $this->flow_has_form->flow->service()->sortNodes();
        }
        $key = array_search($this->flow_has_form->getAttribute('node_id'), $nodes['id']);
        $nodes['name'][$key] = '🚩' . $nodes['name'][$key];
        return $nodes;
    }

    /**
     * 通过form_id获取FlowHasForm模型并赋值给当前类.
     *
     * @param string $form_id
     * @return void
     */
    public function setFlowHasFormByFormId(string $form_id): void
    {
        $flow_has_form = FlowHasForm::query()
            ->where('id', $form_id)
            ->first()
            ->toArray();
        // 子类映射，上述方法获取到的结果类型是Model，需要转换为FlowHasForm类型
        $flow_has_form = new FlowHasForm($flow_has_form);
        $this->flow_has_form = $flow_has_form;
    }
}
