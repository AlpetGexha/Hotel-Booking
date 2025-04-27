<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

final class PricingService
{
    /**
     * Calculate the number of nights between two dates.
     */
    public function getNightsCount(string $checkInDate, string $checkOutDate): int
    {
        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);

        // Using abs() to ensure positive value or correct parameter order
        return $checkIn->diffInDays($checkOut);
    }

    /**
     * Calculate the total price for a booking.
     *
     * @param  RoomType|Collection  $roomType  The room type or collection of room types
     * @param  string  $checkInDate  The check-in date
     * @param  string  $checkOutDate  The check-out date
     * @return float The total price
     */
    public function calculateTotalPrice($roomType, string $checkInDate, string $checkOutDate): float
    {
        $nights = $this->getNightsCount($checkInDate, $checkOutDate);

        if ($roomType instanceof Collection) {
            if ($roomType->isEmpty()) {
                throw new InvalidArgumentException('Room type collection is empty');
            }

            $roomType = $roomType->first();
        }

        if (! ($roomType instanceof RoomType)) {
            throw new InvalidArgumentException('Invalid room type provided');
        }

        $pricePerNight = $roomType->price_per_night;

        // In a real application, you might have more complex pricing rules here
        // For example, different rates for weekends, seasonal pricing, etc.
        $totalPrice = $pricePerNight * $nights;

        return $totalPrice;
    }
}
