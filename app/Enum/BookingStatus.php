<?php

declare(strict_types=1);

namespace App\Enum;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CHECKED_IN = 'checked_in';
    case CHECKED_OUT = 'checked_out';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::CHECKED_IN => 'Checked In',
            self::CHECKED_OUT => 'Checked Out',
            self::CANCELLED => 'Cancelled',
            self::NO_SHOW => 'No Show',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::CONFIRMED => 'blue',
            self::CHECKED_IN => 'green',
            self::CHECKED_OUT => 'purple',
            self::CANCELLED => 'red',
            self::NO_SHOW => 'gray',
        };
    }

    public static function options(): array
    {
        return [
            self::PENDING->value => 'Pending',
            self::CONFIRMED->value => 'Confirmed',
            self::CHECKED_IN->value => 'Checked In',
            self::CHECKED_OUT->value => 'Checked Out',
            self::CANCELLED->value => 'Cancelled',
            self::NO_SHOW->value => 'No Show',
        ];
    }
}
