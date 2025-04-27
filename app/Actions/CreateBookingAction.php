<?php

namespace App\Actions;

use App\Models\RoomType;
use Illuminate\Http\Request;

class CreateBookingAction
{
    public function handle(Request $request, float $totalPrice, int $nights): array
    {
        // Get the room type with amenities
        $roomType = RoomType::with('amenities')->findOrFail($request->room_type_id);

        return [
            'roomType' => $roomType,
            'checkInDate' => $request->check_in_date,
            'checkOutDate' => $request->check_out_date,
            'guests' => $request->guests,
            'nights' => $nights,
            'totalPrice' => $totalPrice,
        ];
    }
}
