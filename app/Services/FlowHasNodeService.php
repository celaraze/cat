<?php

namespace App\Services;

use App\Models\FlowHasNode;
use JetBrains\PhpStorm\ArrayShape;

class FlowHasNodeService
{
    public FlowHasNode $flow_has_node;

    public function __construct(?FlowHasNode $flow_has_node = null)
    {
        $this->flow_has_node = $flow_has_node ?? new FlowHasNode();
    }

    /**
     * 是否有父节点.
     */
    public function isExistParentNode(): bool
    {
        return $this->flow_has_node->parentNode()->count();
    }

    /**
     * 是否是第一个节点.
     */
    public function isFirstNode(): bool
    {
        return ! $this->flow_has_node->getAttribute('parent_node_id');
    }

    /**
     * 是否有子节点.
     */
    public function isExistChildNode(): bool
    {
        return $this->flow_has_node->childNode()->count();
    }

    /**
     * 是否是最后一个节点.
     */
    public function isLastNode(): bool
    {
        return ! FlowHasNode::query()
            ->where('parent_node_id', $this->flow_has_node->getKey())
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
        $this->flow_has_node->setAttribute('name', $data['name']);
        $this->flow_has_node->setAttribute('flow_id', $data['flow_id']);
        $this->flow_has_node->setAttribute('user_id', $data['user_id'] ?? 0);
        $this->flow_has_node->setAttribute('role_id', $data['role_id'] ?? 0);
        $this->flow_has_node->setAttribute('parent_node_id', $data['parent_node_id']);
        $this->flow_has_node->save();

        return $this->flow_has_node;
    }
}
