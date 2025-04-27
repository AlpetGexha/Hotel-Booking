<?php

namespace App\Actions;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\RoomType;
use App\Services\PricingService;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreBookingAction
{
    protected AvaibleRoomAction $availableRoomAction;

    public function __construct(AvaibleRoomAction $availableRoomAction)
    {
        $this->availableRoomAction = $availableRoomAction;
    }

    public function handle(StoreBookingRequest $request, float $totalPrice): Booking|bool
    {
        // Get validated data
        $request->validated();

        // Find an available room for this room type within requested dates
        $availableRoom = $this->availableRoomAction->handle(
            $validated['room_type_id'],
            $validated['check_in_date'],
            $validated['check_out_date']
        );

        if (!$availableRoom) {
            return false;
        }

        // Use database transaction for consistency
        try {
            return DB::transaction(function () use ($validated, $totalPrice, $availableRoom) {
                // Find or create customer
                $customer = Customer::firstOrCreate(
                    [
                        'email' => $validated['email'],
                    ],
                    [
                        'name' => $validated['name'],
                    ]
                );

                // Create booking
                return Booking::create([
                    'room_type_id' => $validated['room_type_id'],
                    'room_id' => $availableRoom->id,
                    'customer_id' => $customer->id,
                    'guests' => $validated['guests'],
                    'check_in' => $validated['check_in_date'],
                    'check_out' => $validated['check_out_date'],
                    'total_price' => $totalPrice,
                    'special_requests' => $validated['special_requests'] ?? null,
                ]);
            });
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
}
