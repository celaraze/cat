<?php

namespace App\Services;

use App\Models\FlowHasNode;
use JetBrains\PhpStorm\ArrayShape;

class FlowHasNodeService extends Service
{
    public function __construct(?FlowHasNode $flow_has_node = null)
    {
        $this->model = $flow_has_node ?? new FlowHasNode();
    }

    /**
     * 是否有父节点.
     */
    public function isExistParentNode(): bool
    {
        return $this->model->parentNode()->count();
    }

    /**
     * 是否是第一个节点.
     */
    public function isFirstNode(): bool
    {
        return ! $this->model->getAttribute('parent_node_id');
    }

    /**
     * 是否有子节点.
     */
    public function isExistChildNode(): bool
    {
        return $this->model->childNode()->count();
    }

    /**
     * 是否是最后一个节点.
     */
    public function isLastNode(): bool
    {
        return ! FlowHasNode::query()
            ->where('parent_node_id', $this->model->getKey())
            ->count();
    }

    /**
     * 创建节点.
     */
    #[ArrayShape([
        'name' => 'string',
        'flow_id' => 'int',
        'user_id' => 'int',
        'role_id' => 'int',
        'parent_node_id' => 'int',
    ])]
    public function create(array $data): FlowHasNode
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('flow_id', $data['flow_id']);
        $this->model->setAttribute('user_id', $data['user_id'] ?? 0);
        $this->model->setAttribute('role_id', $data['role_id'] ?? 0);
        $this->model->setAttribute('parent_node_id', $data['parent_node_id']);
        $this->model->save();

        return $this->model;
    }
}
