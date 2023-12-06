<?php

namespace App\Services;

use App\Models\Flow;
use App\Models\FlowHasForm;
use Exception;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

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
     * 创建流程表单.
     *
     * @throws Exception
     */
    public function createHasForm(string $form_name, string $comment, ?string $payload = null): FlowHasForm
    {
        $node_counts = $this->flow->nodes()->where('parent_node_id', '!=', 0)->count();
        if (! $node_counts) {
            throw new Exception('表单所属流程至少需要一个除申请人外的节点');
        }
        $first_node = $this->flow->nodes->where('parent_node_id', 0)->first();
        $flow_has_form = new FlowHasForm();
        $flow_has_form->setAttribute('name', $form_name);
        $flow_has_form->setAttribute('flow_name', $this->flow->getAttribute('name'));
        $flow_has_form->setAttribute('uuid', Uuid::uuid4());
        $flow_has_form->setAttribute('flow_id', $this->flow->getKey());
        $flow_has_form->setAttribute('applicant_user_id', auth()->id());
        $flow_has_form->setAttribute('current_approve_user_id', $flow_has_form->getAttribute('applicant_user_id'));
        $flow_has_form->setAttribute('comment', $comment);
        $flow_has_form->setAttribute('node_id', $first_node->getKey());
        $flow_has_form->setAttribute('node_name', $first_node->getAttribute('name'));
        if ($payload) {
            $flow_has_form->setAttribute('payload', $payload);
        }
        $flow_has_form->save();

        return $flow_has_form;
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
