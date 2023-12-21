<?php

namespace App\Enums;

class TicketEnum
{
    public static function priorityText(int $state): string
    {
        return match ($state) {
            0 => __('cat.ticket.priority.low'),
            1 => __('cat.ticket.priority.medium'),
            2 => __('cat.ticket.priority.high'),
            3 => __('cat.ticket.priority.urgent'),
        };
    }

    public static function priorityColor(int $state): string
    {
        return match ($state) {
            0 => 'gray',
            1 => 'success',
            2 => 'warning',
            3 => 'danger',
        };
    }

    public static function allPriorityText(): array
    {
        return [
            0 => __('cat.ticket.priority.low'),
            1 => __('cat.ticket.priority.medium'),
            2 => __('cat.ticket.priority.high'),
            3 => __('cat.ticket.priority.urgent'),
        ];
    }

    public static function allStatusText(): array
    {
        return [
            0 => __('cat.ticket.status.idle'),
            1 => __('cat.ticket.status.processing'),
            2 => __('cat.ticket.status.completed'),
        ];
    }

    public static function statusText(int $state): string
    {
        return match ($state) {
            0 => __('cat.ticket.status.idle'),
            1 => __('cat.ticket.status.processing'),
            2 => __('cat.ticket.status.completed'),
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
