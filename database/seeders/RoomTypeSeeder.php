<?php

namespace Database\Seeders;

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
                'amenities' => json_encode(['WiFi', 'TV', 'Air Conditioning', 'Private Bathroom', 'Desk']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'Spacious room with premium amenities and extra comfort.',
                'price_per_night' => 180.00,
                'capacity' => 2,
                'amenities' => json_encode(['WiFi', 'Smart TV', 'Mini Bar', 'Air Conditioning', 'Coffee Machine', 'Safe', 'Desk']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Suite',
                'description' => 'Luxury suite with separate living area and additional amenities.',
                'price_per_night' => 300.00,
                'capacity' => 3,
                'amenities' => json_encode(['WiFi', 'Smart TV', 'Mini Bar', 'Air Conditioning', 'Coffee Machine', 'Safe', 'Sofa', 'Dining Area', 'Premium Toiletries']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Family Room',
                'description' => 'Spacious room designed for families with extra beds and family-friendly amenities.',
                'price_per_night' => 250.00,
                'capacity' => 4,
                'amenities' => json_encode(['WiFi', 'TV', 'Mini Bar', 'Air Conditioning', 'Safe', 'Extra Beds', 'Children Amenities']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Penthouse',
                'description' => 'Luxurious top-floor accommodation with panoramic views and exclusive amenities.',
                'price_per_night' => 500.00,
                'capacity' => 4,
                'amenities' => json_encode(['WiFi', 'Smart TV', 'Mini Bar', 'Air Conditioning', 'Coffee Machine', 'Safe', 'Private Balcony', 'Jacuzzi', 'Premium Toiletries', 'City View']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert all room types in a single query
        RoomType::insert($roomTypes);
    }
}
