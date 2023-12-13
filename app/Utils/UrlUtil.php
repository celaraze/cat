<?php

namespace App\Utils;

class UrlUtil
{
    /**
     * 获取URL上的记录ID.
     */
    public static function getRecordId(?string $uri = null): bool|string
    {
        if (! $uri) {
            $uri = request()->getRequestUri('referer');
            // livewire 的 ajax 转发，不添加的话获取到的永远的 livewire/update
            if ($uri == '/livewire/update') {
                $uri = request()->header('referer');
            }
        }
        $uri = explode('/', $uri);

        return end($uri);
    }
}
