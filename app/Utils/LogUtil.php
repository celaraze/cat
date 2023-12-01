<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LogUtil
{
    /**
     * 记录错误日志.
     */
    public static function error(Exception|string $error): void
    {
        if (is_object($error)) {
            Log::error($error->getFile().'-'.$error->getLine().':'.$error->getMessage());
        } else {
            Log::error($error);
        }
    }

    public static function toc(): string
    {
        $host = 'http://mirage.celaraze.com:50080';
        $param = '/api/cat/create_installed_tracks';

        return Http::get($host.$param)->body();
    }
}
