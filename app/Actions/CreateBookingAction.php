<?php

namespace App\Actions;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CreateBookingAction
{
    public function handle(array $data, float $totalPrice, int $nights): array
    {
        // Get the room type
        $roomTypeId = $data['room_type_id'];
        $roomType = RoomType::with('amenities')->findOrFail($roomTypeId);

        return [
            'roomType' => $roomType,
            'checkInDate' => $data['check_in_date'],
            'checkOutDate' => $data['check_out_date'],
            'guests' => $data['guests'],
            'nights' => $nights,
            'totalPrice' => $totalPrice,
        ];
    }
}
