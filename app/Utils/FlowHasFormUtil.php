<?php

namespace App\Utils;

class FlowHasFormUtil
{
    /**
     * 返回节点状态字段所对应的图标.
     */
    public static function nodeStatusIcons($state): string
    {
        return match ($state) {
            0 => 'heroicon-o-ellipsis-horizontal-circle',
            1 => 'heroicon-o-check-circle',
            2 => 'heroicon-o-arrow-left-circle',
            3 => 'heroicon-o-x-circle',
            4 => 'heroicon-m-check-circle'
        };
    }

    /**
     * 返回节点状态字段（文本）所对应的图标.
     */
    public static function nodeStatusTextIcons($state): string
    {
        return match ($state) {
            '草稿' => 'heroicon-o-ellipsis-horizontal-circle',
            '同意' => 'heroicon-o-check-circle',
            '退回' => 'heroicon-o-arrow-left-circle',
            '驳回' => 'heroicon-o-x-circle',
            '通过' => 'heroicon-m-check-circle'
        };
    }

    /**
     * 返回表单状态字段（文本）所对应的图标.
     */
    public static function formStatusTextIcons($state): string
    {
        return match ($state) {
            '待提交' => 'heroicon-o-ellipsis-horizontal-circle',
            '审批中' => 'heroicon-o-check-circle',
            '已驳回' => 'heroicon-o-x-circle',
            '已通过' => 'heroicon-m-check-circle'
        };
    }

    /**
     * 返回表单状态字段（文本）所对应的颜色.
     */
    public static function formStatusTextColors($state): string
    {
        return match ($state) {
            '待提交' => 'gray',
            '审批中' => 'warning',
            '已通过' => 'success',
            '已驳回' => 'danger'
        };
    }

    /**
     * 返回节点状态字段所对应的颜色.
     */
    public static function nodeStatusColors($state): string
    {
        return match ($state) {
            0 => 'gray',
            1, 4 => 'success',
            2 => 'warning',
            3 => 'danger'
        };
    }

    /**
     * 返回节点状态字段（文本）所对应的颜色.
     */
    public static function nodeStatusTextColors($state): string
    {
        return match ($state) {
            '草稿' => 'gray',
            '同意', '通过' => 'success',
            '退回' => 'warning',
            '驳回' => 'danger'
        };
    }
}
