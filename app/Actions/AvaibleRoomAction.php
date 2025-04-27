<?php

namespace App\Actions;

use App\Models\Room;

class AvaibleRoomAction
{
    /**
     * Find an available room for booking based on room type and date range.
     *
     * @param int $roomTypeId
     * @param string $checkInDate
     * @param string $checkOutDate
     * @return \App\Models\Room|null
     */

    public function handle(int $roomTypeId, string $checkInDate, string $checkOutDate): ?Room
    {
        return Room::where('room_type_id', $roomTypeId)
            ->availableForBooking($checkInDate, $checkOutDate)
            ->first();
    }
}
