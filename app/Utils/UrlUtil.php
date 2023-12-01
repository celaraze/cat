<?php

namespace App\Utils;

class UrlUtil
{
    /**
     * 获取URL上的记录ID.
     */
    public static function getRecordId(string $uri = null): bool|string
    {
        if (! $uri) {
            $uri = request()->getRequestUri();
        }
        $uri = explode('/', $uri);

        return end($uri);
    }
}
