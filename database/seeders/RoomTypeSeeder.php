<?php

namespace Database\Seeders;

use App\Enum\RoomAmenity;
use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prepare data for bulk insert
        $roomTypes = [
            [
                'name' => 'Standard Room',
                'description' => 'Comfortable room with all basic amenities for a pleasant stay.',
                'price_per_night' => 120.00,
                'capacity' => 2,
                'amenities' => json_encode([
                    RoomAmenity::WIFI->value,
                    RoomAmenity::TV->value,
                    RoomAmenity::AIR_CONDITIONING->value,
                    RoomAmenity::PRIVATE_BATHROOM->value,
                    RoomAmenity::DESK->value
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'Spacious room with premium amenities and extra comfort.',
                'price_per_night' => 180.00,
                'capacity' => 2,
                'amenities' => json_encode([
                    RoomAmenity::WIFI->value,
                    RoomAmenity::SMART_TV->value,
                    RoomAmenity::MINI_BAR->value,
                    RoomAmenity::AIR_CONDITIONING->value,
                    RoomAmenity::COFFEE_MACHINE->value,
                    RoomAmenity::SAFE->value,
                    RoomAmenity::DESK->value
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Suite',
                'description' => 'Luxury suite with separate living area and additional amenities.',
                'price_per_night' => 300.00,
                'capacity' => 3,
                'amenities' => json_encode([
                    RoomAmenity::WIFI->value,
                    RoomAmenity::SMART_TV->value,
                    RoomAmenity::MINI_BAR->value,
                    RoomAmenity::AIR_CONDITIONING->value,
                    RoomAmenity::COFFEE_MACHINE->value,
                    RoomAmenity::SAFE->value,
                    RoomAmenity::SOFA->value,
                    RoomAmenity::DINING_AREA->value,
                    RoomAmenity::PREMIUM_TOILETRIES->value
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Family Room',
                'description' => 'Spacious room designed for families with extra beds and family-friendly amenities.',
                'price_per_night' => 250.00,
                'capacity' => 4,
                'amenities' => json_encode([
                    RoomAmenity::WIFI->value,
                    RoomAmenity::TV->value,
                    RoomAmenity::MINI_BAR->value,
                    RoomAmenity::AIR_CONDITIONING->value,
                    RoomAmenity::SAFE->value,
                    RoomAmenity::EXTRA_BEDS->value,
                    RoomAmenity::CHILDREN_AMENITIES->value
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Penthouse',
                'description' => 'Luxurious top-floor accommodation with panoramic views and exclusive amenities.',
                'price_per_night' => 500.00,
                'capacity' => 4,
                'amenities' => json_encode([
                    RoomAmenity::WIFI->value,
                    RoomAmenity::SMART_TV->value,
                    RoomAmenity::MINI_BAR->value,
                    RoomAmenity::AIR_CONDITIONING->value,
                    RoomAmenity::COFFEE_MACHINE->value,
                    RoomAmenity::SAFE->value,
                    RoomAmenity::BALCONY->value,
                    RoomAmenity::JACUZZI->value,
                    RoomAmenity::PREMIUM_TOILETRIES->value,
                    RoomAmenity::CITY_VIEW->value
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert all room types in a single query
        RoomType::insert($roomTypes);
    }
}
