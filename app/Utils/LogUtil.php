<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\Log;

class LogUtil
{
    /**
     * 记录错误日志.
     *
     * @param Exception|string $error
     * @return void
     */
    public static function error(Exception|string $error): void
    {
        if (is_object($error)) {
            Log::error($error->getFile() . '-' . $error->getLine() . ':' . $error->getMessage());
        } else {
            Log::error($error);
        }
    }
}
