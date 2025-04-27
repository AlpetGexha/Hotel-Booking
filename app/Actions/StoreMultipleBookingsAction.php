<?php

namespace App\Actions;

use App\Http\Requests\StoreMultipleBookingsRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StoreMultipleBookingsAction
{
    protected AvaibleRoomAction $availableRoomAction;

    public function __construct(AvaibleRoomAction $availableRoomAction)
    {
        $this->availableRoomAction = $availableRoomAction;
    }

    public function handle(StoreMultipleBookingsRequest $request, array $roomPrices): Collection|bool
    {
        try {
            // Get validated data
            $validated = $request->validated();

            DB::beginTransaction();

            // Find or create customer
            $customer = Customer::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                ]
            );

            $bookings = new Collection();
            $rooms = Room::whereIn('id', $validated['room_ids'])->with('roomType')->get();

            // Create a booking for each room
            foreach ($rooms as $room) {
                // Verify room availability again
                $availableRoom = $this->availableRoomAction->handle(
                    $room->room_type_id,
                    $validated['check_in_date'],
                    $validated['check_out_date']
                );

                if (!$availableRoom) {
                    throw new Exception("Room {$room->room_number} is no longer available.");
                }

                // Get the pre-calculated price for this room
                $totalPrice = $roomPrices[$room->id]['total_price'] ?? 0;

                // Create booking record
                $booking = Booking::create([
                    'room_id' => $room->id,
                    'room_type_id' => $room->room_type_id,
                    'customer_id' => $customer->id,
                    'check_in' => $validated['check_in_date'],
                    'check_out' => $validated['check_out_date'],
                    'guests' => min($validated['guests'], $room->roomType->capacity),
                    'total_price' => $totalPrice,
                    'special_requests' => $validated['special_requests'] ?? null,
                ]);

                $bookings->push($booking);
            }

            DB::commit();

            return $bookings;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return false;
        }
    }
}
