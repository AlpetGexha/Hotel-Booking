<?php

namespace App\Services;

use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class PricingService
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

    /**
     * Find an available room for the given room type and date range.
     */
    public function findAvailableRoom(int $roomTypeId, string $checkInDate, string $checkOutDate): ?\App\Models\Room
    {
        return \App\Models\Room::where('room_type_id', $roomTypeId)
            ->where('is_available', true)
            ->whereDoesntHave('bookings', function ($query) use ($checkInDate, $checkOutDate) {
                $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                    // Room is not booked during the requested period
                    // Check if existing booking overlaps with requested dates
                    $q->whereBetween('check_in', [$checkInDate, $checkOutDate])
                        ->orWhereBetween('check_out', [$checkInDate, $checkOutDate])
                        ->orWhere(function ($innerQuery) use ($checkInDate, $checkOutDate) {
                            // Or if requested dates are within an existing booking
                            $innerQuery->where('check_in', '<=', $checkInDate)
                                ->where('check_out', '>=', $checkOutDate);
                        });
                });
            })
            ->first();
    }
}
