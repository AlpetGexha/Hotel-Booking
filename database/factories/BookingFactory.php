<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
final class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('now', '+3 months');
        $checkOut = Carbon::instance($checkIn)->addDays(fake()->numberBetween(1, 14));

        $roomTypeCount = RoomType::count();

        return [
            'customer_id' => Customer::factory(),
            'room_type_id' => random_int(1, $roomTypeCount),
            'guests' => fake()->numberBetween(1, 4),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'total_price' => fake()->randomFloat(2, 100, 5000),
            'special_requests' => fake()->optional(0.7)->text(200),
        ];
    }

    /**
     * Assign an available room to the booking.
     */
    public function withAssignedRoom(): static
    {
        return $this->afterCreating(function ($booking): void {
            // Convert dates to Carbon instances if needed
            $checkIn = $booking->check_in instanceof Carbon ? $booking->check_in : Carbon::parse($booking->check_in);
            $checkOut = $booking->check_out instanceof Carbon ? $booking->check_out : Carbon::parse($booking->check_out);

            // Find available room of the required type for the booking dates
            $availableRoom = Room::where('room_type_id', $booking->room_type_id)
                ->where('is_available', true)
                ->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut): void {
                    $query->where(function ($q) use ($checkIn, $checkOut): void {
                        // Check for overlapping bookings
                        $q->whereBetween('check_in', [$checkIn, $checkOut])
                            ->orWhereBetween('check_out', [$checkIn, $checkOut])
                            ->orWhere(function ($innerQuery) use ($checkIn, $checkOut): void {
                                $innerQuery->where('check_in', '<=', $checkIn)
                                    ->where('check_out', '>=', $checkOut);
                            });
                    });
                })
                ->first();

            if ($availableRoom) {
                $booking->update(['room_id' => $availableRoom->id]);
            }
        });
    }
}
