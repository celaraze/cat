<?php

namespace App\Utils;

use Exception;
use Filament\Notifications\Notification;

class NotificationUtil
{
    public static function make(bool $result, string|Exception $body, bool $always = false): void
    {
        if (is_object($body)) {
            $body = $body->getMessage();
        }
        $notification = Notification::make()
            ->body($body);
        if ($result) {
            $notification->title(__('cat.notification.success'));
            $notification->success();
        } else {
            $notification->title(__('cat.notification.failure'));
            $notification->danger();
        }
        if ($always) {
            $notification->persistent();
        }
        $notification->send();
    }
}
