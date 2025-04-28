<?php

declare(strict_types=1);

namespace App\Enum;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PARTIAL = 'partial';
    case PAID = 'paid';
    case REFUNDED = 'refunded';
    case FAILED = 'failed';

    /**
     * Get a display label for the enum value
     */
    public function label(): string
    {
        return match($this) {
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
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::PARTIAL => 'blue',
            self::PAID => 'green',
            self::REFUNDED => 'purple',
            self::FAILED => 'red',
        };
    }

    /**
     * Get an icon for this payment status
     */
    public function icon(): string
    {
        return match($this) {
            self::PENDING => 'heroicon-o-clock',
            self::PARTIAL => 'heroicon-o-currency-dollar',
            self::PAID => 'heroicon-o-check-circle',
            self::REFUNDED => 'heroicon-o-arrow-path',
            self::FAILED => 'heroicon-o-x-circle',
        };
    }

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
}
