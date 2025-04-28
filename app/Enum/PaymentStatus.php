<?php

declare(strict_types=1);

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum PaymentStatus: string implements HasColor, HasIcon
{
    case PENDING = 'pending';
    case PARTIAL = 'partial';
    case PAID = 'paid';
    case REFUNDED = 'refunded';
    case FAILED = 'failed';

    /**
     * Get all enum values as an array suitable for select options
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->label()])
            ->toArray();
    }

    /**
     * Get a display label for the enum value
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Payment',
            self::PARTIAL => 'Partially Paid',
            self::PAID => 'Fully Paid',
            self::REFUNDED => 'Refunded',
            self::FAILED => 'Payment Failed',
        };
    }

    /**
     * Get a color associated with this payment status
     */
    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PARTIAL => 'warning',
            self::PAID => 'success',
            self::REFUNDED => 'info',
            self::FAILED => 'danger',
        };
    }

    /**
     * Get an icon for this payment status
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-clock',
            self::PARTIAL => 'heroicon-o-currency-dollar',
            self::PAID => 'heroicon-o-check-circle',
            self::REFUNDED => 'heroicon-o-arrow-path',
            self::FAILED => 'heroicon-o-x-circle',
        };
    }
}
