<?php

declare(strict_types=1);

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum PaymentMethod: string implements HasColor, HasIcon
{
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case PAYPAL = 'paypal';

    /**
     * Get a display label for the enum value
     */
    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::CREDIT_CARD => 'Credit Card',
            self::PAYPAL => 'PayPal',
        };
    }

    /**
     * Get a color associated with this payment method
     */
    public function getColor(): string
    {
        return match ($this) {
            self::CASH => 'success',
            self::CREDIT_CARD => 'primary',
            // self::DEBIT_CARD => 'blue',
            // self::BANK_TRANSFER => 'cyan',
            self::PAYPAL => 'info',
        };
    }

    /**
     * Get an icon for this payment method
     */
    public function getIcon(): string
    {
        return match ($this) {
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
            ->mapWithKeys(fn(self $method) => [$method->value => $method->label()])
            ->toArray();
    }
}
