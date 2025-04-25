<?php

namespace App\Enum;

enum RoomStatus: string
{
    case CLEAN = 'clean';
    case DIRTY = 'dirty';
    case MAINTENANCE = 'maintenance';
    case OUT_OF_SERVICE = 'out_of_service';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}