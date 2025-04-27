<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Room;
use Illuminate\Http\Request;

final class CreateMultipleBookingsAction
{
    public function handle(Request $request, array $roomPrices, int $nights, float $totalPrice): array
    {
        // Load rooms with their room types
        $rooms = Room::with('roomType.amenities')
            ->whereIn('id', $request->room_ids)
            ->get();

        return [
            'rooms' => $rooms,
            'roomPrices' => $roomPrices,
            'checkInDate' => $request->check_in_date,
            'checkOutDate' => $request->check_out_date,
            'guests' => $request->guests,
            'nights' => $nights,
            'totalPrice' => $totalPrice,
        ];
    }
}
