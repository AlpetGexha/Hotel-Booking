<?php

namespace App\Actions;

use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;

class CreateMultipleBookingsAction
{
    public function handle(array $data, array $roomPrices, int $nights, float $totalPrice): array
    {
        // Load rooms with their room types
        $roomIds = $data['room_ids'];
        $rooms = Room::with('roomType.amenities')
            ->whereIn('id', $roomIds)
            ->get();

        return [
            'rooms' => $rooms,
            'roomPrices' => $roomPrices,
            'checkInDate' => $data['check_in_date'],
            'checkOutDate' => $data['check_out_date'],
            'guests' => $data['guests'],
            'nights' => $nights,
            'totalPrice' => $totalPrice,
        ];
    }
}
