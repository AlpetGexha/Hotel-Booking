<?php

declare(strict_types=1);

namespace App\Enum;

use Filament\Support\Contracts\HasColor;

enum BookingStatus: string implements HasColor
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CHECKED_IN = 'checked_in';
    case CHECKED_OUT = 'checked_out';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';

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

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::CHECKED_IN => 'Checked In',
            self::CHECKED_OUT => 'Checked Out',
            self::CANCELLED => 'Cancelled',
            self::NO_SHOW => 'No Show',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'primary',
            self::CHECKED_IN => 'success',
            self::CHECKED_OUT => 'info',
            self::CANCELLED => 'danger',
            self::NO_SHOW => 'dark',
        };
    }
}
