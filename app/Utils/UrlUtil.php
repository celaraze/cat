<?php

namespace App\Utils;

class UrlUtil
{
    /**
     * 获取URL上的记录ID.
     *
     * @param string|null $uri
     * @return bool|string
     */
    public static function getRecordId(string $uri = null): bool|string
    {
        if (!$uri) {
            $uri = request()->getRequestUri();
        }
        $uri = explode('/', $uri);
        return end($uri);
    }
}
