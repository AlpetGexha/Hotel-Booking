<?php

namespace App\Actions;

use App\Exceptions\BookingException;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class StoreBookingAction
{
    public function __construct(
        protected readonly AvaibleRoomAction $availableRoomAction
    ) {}

    public function handle(StoreBookingRequest $request, float $totalPrice): Booking
    {
        // Call validated() to ensure validation has run
        $request->validated();

        // Find an available room for this room type within requested dates
        $availableRoom = $this->availableRoomAction->handle(
            $request->room_type_id,
            $request->check_in_date,
            $request->check_out_date
        );

        if (!$availableRoom instanceof \App\Models\Room) {
            throw new BookingException('No rooms of this type are available for the selected dates');
        }

        // Use database transaction for consistency
        return DB::transaction(function () use ($request, $totalPrice, $availableRoom) {
            // Find or create customer
            $customer = Customer::firstOrCreate(
                ['email' => $request->email],
                ['name' => $request->name]
            );

            // Create booking
            return Booking::create([
                'room_type_id' => $request->room_type_id,
                'room_id' => $availableRoom->id,
                'customer_id' => $customer->id,
                'guests' => $request->guests,
                'check_in' => $request->check_in_date,
                'check_out' => $request->check_out_date,
                'total_price' => $totalPrice,
                'special_requests' => $request->special_requests,
            ]);
        });
    }
}
