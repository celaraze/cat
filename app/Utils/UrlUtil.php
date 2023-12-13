<?php

namespace App\Utils;

use Exception;

class UrlUtil
{
    /**
     * 获取URL上的记录ID.
     * todo 这地方以后肯定得改，filament 好多页面之间的模型都不能互相取值，只能通过 URL 传参，这样太不优雅了
     *
     * @throws Exception
     */
    public static function getRecordId(int $index = 0, ?string $uri = null): bool|string
    {
        try {
            if (! $uri) {
                $uri = request()->getRequestUri('referer');
                // livewire 的 ajax 转发，不添加的话获取到的永远的 livewire/update
                if ($uri == '/livewire/update') {
                    $uri = request()->header('referer');
                }
            }
            $uri = explode('/', $uri);

            return $uri[count($uri) + $index - 1];
        } catch (Exception $exception) {
            throw new Exception('URL 解析错误。');
        }

    }
}
