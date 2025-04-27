<?php

namespace App\Actions;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class AvaibleRoomAction
{
    /**
     * Find an available room for booking based on room type and date range.
     */
    public function handle(int $roomTypeId, string $checkInDate, string $checkOutDate): ?Room
    {
        return Room::where('room_type_id', $roomTypeId)
            ->availableForBooking($checkInDate, $checkOutDate)
            ->first();
    }

    /**
     * Find available room alternatives when exact capacity isn't available.
     *
     * @param int $guests Number of guests
     * @param string $checkInDate Check-in date
     * @param string $checkOutDate Check-out date
     * @param int|null $roomTypeId Optional room type to check first
     * @return array Contains 'exact' (Room|null), 'larger' (Collection), 'multiple' (Collection), and 'message' (string|null)
     */
    public function findAlternatives(int $guests, string $checkInDate, string $checkOutDate, ?int $roomTypeId = null): array
    {
        $result = [
            'exact' => null,                  // Exact match room (if found)
            'larger' => new Collection(),     // Larger capacity alternative rooms (if no exact match)
            'multiple' => new Collection(),   // Multiple room combinations (if no single room fits all guests)
            'message' => null,                // Suggestion message for the user
        ];

        // First, try to find an exact match if room type is specified
        if ($roomTypeId) {
            $result['exact'] = $this->handle($roomTypeId, $checkInDate, $checkOutDate);

            // If exact match is found, return early
            if ($result['exact']) {
                return $result;
            }
        }

        // Try to find rooms with exactly the requested capacity
        $exactCapacityRooms = Room::whereHas('roomType', function($query) use ($guests) {
                $query->where('capacity', '=', $guests);
            })
            ->with('roomType')
            ->availableForBooking($checkInDate, $checkOutDate)
            ->get();

        if ($exactCapacityRooms->isNotEmpty()) {
            $result['exact'] = $exactCapacityRooms->first();
            return $result;
        }

        // If no exact match, look for alternatives based on guest count
        if ($guests === 1) {
            // For a single guest, suggest a larger room
            $largerRooms = Room::whereHas('roomType', function($query) {
                    $query->where('capacity', '>', 1)->orderBy('capacity', 'asc');
                })
                ->with('roomType')
                ->availableForBooking($checkInDate, $checkOutDate)
                ->get();

            if ($largerRooms->isNotEmpty()) {
                $result['larger'] = $largerRooms;
                $result['message'] = 'No rooms for 1 guest, but we found a larger room.';
            }
        } else {
            // First try to find a larger room that can fit all guests
            $largerRooms = Room::whereHas('roomType', function($query) use ($guests) {
                    $query->where('capacity', '>=', $guests);
                })
                ->with('roomType')
                ->availableForBooking($checkInDate, $checkOutDate)
                ->get();

            if ($largerRooms->isNotEmpty()) {
                $result['larger'] = $largerRooms;
                $result['message'] = 'We don\'t have any rooms available for exactly ' . $guests . ' ' .
                    Str::plural('person', $guests) . ', but we have larger rooms that can accommodate your group.';
            } else {
                // If no single room can fit all guests, find combinations of smaller rooms
                $availableRooms = Room::with('roomType')
                    ->availableForBooking($checkInDate, $checkOutDate)
                    ->get()
                    ->sortByDesc(function($room) {
                        return $room->roomType->capacity;
                    });

                if ($availableRooms->isNotEmpty()) {
                    // Calculate total available capacity
                    $totalCapacity = $availableRooms->sum(function($room) {
                        return $room->roomType->capacity;
                    });

                    // Only suggest multiple rooms if the total capacity can accommodate all guests
                    if ($totalCapacity >= $guests) {
                        $result['multiple'] = $availableRooms;
                        $result['message'] = "No single room available for {$guests} guests. You can book multiple rooms to fit everyone.";
                    }
                }
            }
        }

        return $result;
    }
}
