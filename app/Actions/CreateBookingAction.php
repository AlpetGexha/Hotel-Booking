<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\RoomType;
use Illuminate\Http\Request;

final class CreateBookingAction
{
    public function handle(Request $request, float $totalPrice, int|float $nights): array
    {
        // Get the room type with amenities
        $roomType = RoomType::with('amenities')->findOrFail($request->room_type_id);

        return [
            'roomType' => $roomType,
            'checkInDate' => $request->check_in_date,
            'checkOutDate' => $request->check_out_date,
            'guests' => (int) $request->guests,
            'nights' => $nights,
            'totalPrice' => $totalPrice,
        ];
    }
}
