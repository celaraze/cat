<?php

namespace App\Utils;

use App\Models\Information\Device;
use App\Models\Information\Part;
use App\Models\Information\Software;

class AssetUtil
{
    /**
     * 类和名称映射关系.
     *
     * @param string $class_name
     * @return string
     */
    public static function mapper(string $class_name): string
    {
        $data = [
            Device::class => '设备',
            Part::class => '配件',
            Software::class => '软件',
        ];
        return $data[$class_name] ?? '无';
    }
}
