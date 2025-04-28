<?php

declare(strict_types=1);

namespace App\Enum;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case PAYPAL = 'paypal';

    /**
     * Get a display label for the enum value
     */
    public function label(): string
    {
        return match($this) {
            self::CASH => 'Cash',
            self::CREDIT_CARD => 'Credit Card',
            self::PAYPAL => 'PayPal',
        };
    }

    /**
     * Get a color associated with this payment method
     */
    public function color(): string
    {
        return match($this) {
            self::CASH => 'emerald',
            self::CREDIT_CARD => 'indigo',
            // self::DEBIT_CARD => 'blue',
            // self::BANK_TRANSFER => 'cyan',
            self::PAYPAL => 'violet',
        };
    }

    /**
     * Get an icon for this payment method
     */
    public function icon(): string
    {
        return match($this) {
            self::CASH => 'heroicon-o-banknotes',
            self::CREDIT_CARD => 'heroicon-o-credit-card',
            // self::DEBIT_CARD => 'heroicon-o-credit-card',
            // self::BANK_TRANSFER => 'heroicon-o-building-library',
            self::PAYPAL => 'heroicon-o-currency-dollar',
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
            ->mapWithKeys(fn (self $method) => [$method->value => $method->label()])
            ->toArray();
    }
}
