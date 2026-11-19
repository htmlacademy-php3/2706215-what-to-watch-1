<?php

namespace App\Enum;

enum FilmStatus: string
{
    case PENDING = 'pending';
    case MODERATE = 'moderate';
    case READY = 'ready';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
