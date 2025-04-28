<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\BookingException;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class StoreBookingAction
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

        if (! $availableRoom instanceof \App\Models\Room) {
            throw new BookingException('No rooms of this type are available for the selected dates');
        }

        // Use database transaction for consistency
        return DB::transaction(function () use ($request, $totalPrice, $availableRoom) {
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
                        'name' => $request->name,
                        'phone' => $request->phone ?? null,
                    ]
                );
            }

            // Create booking
            return Booking::create([
                'room_type_id' => $request->room_type_id,
                'room_id' => $availableRoom->id,
                'customer_id' => $customer->id,
                'booker_id' => ($request->booking_for === 'other' && Auth::check()) ? Auth::id() : null,
                'guests' => (int) $request->guests,
                'check_in' => $request->check_in_date,
                'check_out' => $request->check_out_date,
                'total_price' => $totalPrice,
                'special_requests' => $request->special_requests,
            ]);
        });
    }
}
