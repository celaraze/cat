<?php

namespace App\Utils;

class InventoryUtil
{
    /**
     * 盘点字段映射.
     */
    public static function mapper(int $check): string
    {
        $data = [
            0 => '未盘点',
            1 => '在库',
            2 => '标记缺失',
        ];

        return $data[$check] ?? '无';
    }
}
