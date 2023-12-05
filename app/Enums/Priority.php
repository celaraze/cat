<?php

namespace App\Enums;

class Priority
{
    const LOW = 0;

    const MEDIUM = 1;

    const HIGH = 2;

    const URGENT = 3;

    public static function array(): array
    {
        return [
            self::LOW => '低',
            self::MEDIUM => '中',
            self::HIGH => '高',
            self::URGENT => '紧急',
        ];
    }

    public static function colors(): array
    {
        return [
            self::LOW => 'gray',
            self::MEDIUM => 'blue',
            self::HIGH => 'yellow',
            self::URGENT => 'red',
        ];
    }
}
