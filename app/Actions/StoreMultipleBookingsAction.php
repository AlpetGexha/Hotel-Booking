<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\BookingException;
use App\Http\Requests\StoreMultipleBookingsRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

final class StoreMultipleBookingsAction
{
    public function __construct(
        protected readonly AvaibleRoomAction $availableRoomAction
    ) {}

    public function handle(StoreMultipleBookingsRequest $request, array $roomPrices): Collection
    {
        // Call validated() to ensure validation has run
        $request->validated();

        try {
            DB::beginTransaction();

            // Determine customer based on booking_for
            $customer = null;

            if ($request->booking_for === 'self' && Auth::check()) {
                // User is booking for themselves
                $user = Auth::user();

                // Find or create customer record for this user
                $customer = Customer::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ]
                );
            } else {
                // User is booking for someone else or is a guest
                $customer = Customer::firstOrCreate(
                    ['email' => $request->email],
                    [
                        'name' => $request->first_name . ' ' . $request->last_name,
                        'phone' => $request->phone ?? null,
                    ]
                );
            }

            $bookings = new Collection;
            $rooms = Room::whereIn('id', $request->room_ids)
                ->with('roomType')
                ->get();

            // Create a booking for each room
            foreach ($rooms as $room) {
                // Verify room availability again
                $availableRoom = $this->availableRoomAction->handle(
                    $room->room_type_id,
                    $request->check_in_date,
                    $request->check_out_date
                );

                if (! $availableRoom instanceof Room) {
                    throw new BookingException("Room {$room->room_number} is no longer available.");
                }

                // Get the pre-calculated price for this room
                $totalPrice = $roomPrices[$room->id]['total_price'] ?? 0;

                // Create booking record
                $booking = Booking::create([
                    'room_id' => $room->id,
                    'room_type_id' => $room->room_type_id,
                    'customer_id' => $customer->id,
                    'booker_id' => ($request->booking_for === 'other' && Auth::check()) ? Auth::id() : null,
                    'check_in' => $request->check_in_date,
                    'check_out' => $request->check_out_date,
                    'guests' => min((int) $request->guests, $room->roomType->capacity),
                    'total_price' => $totalPrice,
                    'special_requests' => $request->special_requests,
                    'payment_method' => $request->payment_method,
                    'payment_status' => \App\Enum\PaymentStatus::PENDING,
                ]);

                $bookings->push($booking);
            }

            DB::commit();

            return $bookings;
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            throw $e instanceof BookingException ? $e : new BookingException($e->getMessage(), 0, $e);
        }
    }
}
