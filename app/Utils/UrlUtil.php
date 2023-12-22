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
    public static function getRecordId(?string $uri = null): bool|string
    {
        try {
            // 如果没有传入 uri，就从 request 中获取
            if (! $uri) {
                $uri = request()->getRequestUri();
            }

            // livewire 的 ajax 转发，不添加的话获取到的永远是 livewire/update
            if ($uri == '/livewire/update') {
                $uri = request()->header('referer');
            }

            // 获取 URL 的路径部分
            $path = parse_url($uri, PHP_URL_PATH);

            // 正则表达式获取记录 ID
            if (preg_match('/\/(\d+)\/?/', $path, $matches)) {
                return $matches[1];
            }
        } catch (Exception $exception) {
            throw new Exception(__('cat/auth.url_error'));
        }
    }
}
