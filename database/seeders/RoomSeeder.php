<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all room types once with their IDs
        $roomTypeIds = RoomType::pluck('id')->toArray();

        // Define available floors
        $floors = [1, 2, 3, 4, 5];

        // Prepare data for bulk insert
        $rooms = [];
        $now = now();

        foreach ($floors as $floor) {
            // Define how many rooms are on each floor (between 10-20)
            $roomsOnFloor = rand(10, 20);

            for ($roomNumberSuffix = 1; $roomNumberSuffix <= $roomsOnFloor; $roomNumberSuffix++) {
                // Create a room number in format: floor + room sequence (e.g. 101, 102, 201, 202)
                $roomNumber = $floor * 100 + $roomNumberSuffix;

                // Get a random room type ID
                $randomRoomTypeId = $roomTypeIds[array_rand($roomTypeIds)];

                // Add to rooms array for bulk insert
                $rooms[] = [
                    'room_number' => (string)$roomNumber,
                    'floor' => $floor,
                    'room_type_id' => $randomRoomTypeId,
                    'is_available' => rand(1, 100) <= 90, // 90% chance of being available
                    'notes' => rand(1, 100) <= 30 ? fake()->sentence() : null, // 30% chance of having notes
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Use chunk insert for better performance with larger datasets
        foreach (array_chunk($rooms, 100) as $chunk) {
            Room::insert($chunk);
        }
    }
}
