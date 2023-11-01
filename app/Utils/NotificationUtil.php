<?php

namespace App\Utils;

use Exception;
use Filament\Notifications\Notification;

class NotificationUtil
{
    public static function make(bool $result, string|Exception $body): void
    {
        if (is_object($body)) {
            $body = $body->getMessage();
        }
        $notification = Notification::make()
            ->body($body);
        if ($result) {
            $notification->title('成功');
            $notification->success();
        } else {
            $notification->title('失败');
            $notification->danger();
        }
        $notification->send();
    }
}
