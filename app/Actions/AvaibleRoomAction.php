<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

// for this much code the best apporach is to use a Sercvice class but this is fine for me now
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
     * @param  int  $guests  Number of guests
     * @param  string  $checkInDate  Check-in date
     * @param  string  $checkOutDate  Check-out date
     * @param  int|null  $roomTypeId  Optional room type to check first
     * @return array Contains 'exact' (Room|null), 'larger' (Collection), 'multiple' (Collection), and 'message' (string|null)
     */
    public function findAlternatives(int $guests, string $checkInDate, string $checkOutDate, ?int $roomTypeId = null): array
    {
        $result = $this->initializeResultArray();

        // Try to find exact match by room type if specified
        if ($roomTypeId && $this->findExactRoomTypeMatch($result, $roomTypeId, $checkInDate, $checkOutDate)) {
            return $result;
        }

        // Try to find rooms with exactly the requested capacity
        if ($this->findExactCapacityRooms($result, $guests, $checkInDate, $checkOutDate)) {
            return $result;
        }

        // Handle single guest case
        if ($guests === 1) {
            $this->findLargerRoomsForSingleGuest($result, $checkInDate, $checkOutDate);

            return $result;
        }

        // Handle multiple guests
        $this->findAlternativesForMultipleGuests($result, $guests, $checkInDate, $checkOutDate);

        return $result;
    }

    /**
     * Initialize the result array with default values.
     */
    private function initializeResultArray(): array
    {
        return [
            'exact' => null,                  // Exact match room (if found)
            'larger' => new Collection,     // Larger capacity alternative rooms (if no exact match)
            'multiple' => new Collection,   // Multiple room combinations (if no single room fits all guests)
            'message' => null,                // Suggestion message for the user
        ];
    }

    /**
     * Find an exact match for the specified room type.
     */
    private function findExactRoomTypeMatch(array &$result, int $roomTypeId, string $checkInDate, string $checkOutDate): bool
    {
        $result['exact'] = $this->handle($roomTypeId, $checkInDate, $checkOutDate);

        return $result['exact'] instanceof \App\Models\Room;
    }

    /**
     * Find rooms with exactly the requested capacity.
     */
    private function findExactCapacityRooms(array &$result, int $guests, string $checkInDate, string $checkOutDate): bool
    {
        $exactCapacityRooms = Room::whereHas('roomType', function ($query) use ($guests): void {
            $query->where('capacity', '=', $guests);
        })
            ->with('roomType')
            ->availableForBooking($checkInDate, $checkOutDate)
            ->get();

        if ($exactCapacityRooms->isEmpty()) {
            return false;
        }

        $result['exact'] = $exactCapacityRooms->first();

        return true;
    }

    /**
     * Find larger rooms for a single guest.
     */
    private function findLargerRoomsForSingleGuest(array &$result, string $checkInDate, string $checkOutDate): void
    {
        $largerRooms = Room::whereHas('roomType', function ($query): void {
            $query->where('capacity', '>', 1)->orderBy('capacity', 'asc');
        })
            ->with('roomType')
            ->availableForBooking($checkInDate, $checkOutDate)
            ->get();

        if ($largerRooms->isNotEmpty()) {
            $result['larger'] = $largerRooms;
            $result['message'] = 'No rooms for 1 guest, but we found a larger room.';
        }
    }

    /**
     * Find alternatives for multiple guests.
     */
    private function findAlternativesForMultipleGuests(array &$result, int $guests, string $checkInDate, string $checkOutDate): void
    {
        // Try finding a larger room first
        $this->findLargerRoomForMultipleGuests($result, $guests, $checkInDate, $checkOutDate);

        // If no larger room found, try combinations of smaller rooms
        if ($result['larger']->isEmpty()) {
            $this->findMultipleRoomsForGuests($result, $guests, $checkInDate, $checkOutDate);
        }
    }

    /**
     * Find a larger room that can accommodate all guests.
     */
    private function findLargerRoomForMultipleGuests(array &$result, int $guests, string $checkInDate, string $checkOutDate): void
    {
        $largerRooms = Room::whereHas('roomType', function ($query) use ($guests): void {
            $query->where('capacity', '>=', $guests);
        })
            ->with('roomType')
            ->availableForBooking($checkInDate, $checkOutDate)
            ->get();

        if ($largerRooms->isNotEmpty()) {
            $result['larger'] = $largerRooms;
            $result['message'] = 'We don\'t have any rooms available for exactly ' . $guests . ' ' .
                Str::plural('person', $guests) . ', but we have larger rooms that can accommodate your group.';
        }
    }

    /**
     * Find combinations of smaller rooms to accommodate all guests.
     */
    private function findMultipleRoomsForGuests(array &$result, int $guests, string $checkInDate, string $checkOutDate): void
    {
        $availableRooms = Room::with('roomType')
            ->availableForBooking($checkInDate, $checkOutDate)
            ->get()
            ->sortByDesc(fn ($room) => $room->roomType->capacity);

        if ($availableRooms->isEmpty()) {
            return;
        }

        $totalCapacity = $this->calculateTotalCapacity($availableRooms);

        if ($totalCapacity >= $guests) {
            $result['multiple'] = $availableRooms;
            $result['message'] = "No single room available for {$guests} guests. You can book multiple rooms to fit everyone.";
        }
    }

    /**
     * Calculate the total capacity of a collection of rooms.
     */
    private function calculateTotalCapacity(Collection $rooms): int
    {
        return $rooms->sum(fn ($room) => $room->roomType->capacity);
    }
}
