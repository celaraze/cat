<?php

namespace App\Enums;

class TicketEnum
{
    public static function priorityText(int $state): string
    {
        return match ($state) {
            0 => '低',
            1 => '中',
            2 => '高',
            3 => '紧急',
        };
    }

    public static function priorityColor(int $state): string
    {
        return match ($state) {
            0 => 'gray',
            1 => 'blue',
            2 => 'yellow',
            3 => 'danger',
        };
    }

    public static function allPriorityText(): array
    {
        return [
            0 => '低',
            1 => '中',
            2 => '高',
            3 => '紧急',
        ];
    }

    public static function statusText(int $state): string
    {
        return match ($state) {
            0 => '空闲',
            1 => '进行',
            2 => '完成',
        };
    }

    public static function statusColor(int $state): string
    {
        return match ($state) {
            0 => 'gray',
            1 => 'warning',
            2 => 'success',
        };
    }
}
