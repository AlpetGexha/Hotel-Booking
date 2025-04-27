<?php

namespace Database\Seeders;

use App\Enum\RoomAmenity;
use App\Models\Amenity;
use App\Models\RoomType;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define room types with their amenities using enum cases directly
        $roomTypesData = [
            [
                'name' => 'Standard Room',
                'description' => 'Comfortable room with all basic amenities for a pleasant stay.',
                'price_per_night' => 120.00,
                'capacity' => 2,
                'size' => 25,
                'amenities' => [
                    RoomAmenity::WIFI,
                    RoomAmenity::TV,
                    RoomAmenity::AIR_CONDITIONING,
                    RoomAmenity::PRIVATE_BATHROOM,
                    RoomAmenity::DESK,
                ],
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'Spacious room with premium amenities and extra comfort.',
                'price_per_night' => 180.00,
                'capacity' => 2,
                'size' => 35,
                'amenities' => [
                    RoomAmenity::WIFI,
                    RoomAmenity::SMART_TV,
                    RoomAmenity::MINI_BAR,
                    RoomAmenity::AIR_CONDITIONING,
                    RoomAmenity::COFFEE_MACHINE,
                    RoomAmenity::SAFE,
                    RoomAmenity::DESK,
                ],
            ],
            [
                'name' => 'Suite',
                'description' => 'Luxury suite with separate living area and additional amenities.',
                'price_per_night' => 300.00,
                'capacity' => 3,
                'size' => 60,
                'amenities' => [
                    RoomAmenity::WIFI,
                    RoomAmenity::SMART_TV,
                    RoomAmenity::MINI_BAR,
                    RoomAmenity::AIR_CONDITIONING,
                    RoomAmenity::COFFEE_MACHINE,
                    RoomAmenity::SAFE,
                    RoomAmenity::SOFA,
                    RoomAmenity::DINING_AREA,
                    RoomAmenity::PREMIUM_TOILETRIES,
                ],
            ],
            [
                'name' => 'Family Room',
                'description' => 'Spacious room designed for families with extra beds and family-friendly amenities.',
                'price_per_night' => 250.00,
                'capacity' => 4,
                'size' => 55,
                'amenities' => [
                    RoomAmenity::WIFI,
                    RoomAmenity::TV,
                    RoomAmenity::MINI_BAR,
                    RoomAmenity::AIR_CONDITIONING,
                    RoomAmenity::SAFE,
                    RoomAmenity::EXTRA_BEDS,
                    RoomAmenity::CHILDREN_AMENITIES,
                ],
            ],
            [
                'name' => 'Penthouse',
                'description' => 'Luxurious top-floor accommodation with panoramic views and exclusive amenities.',
                'price_per_night' => 500.00,
                'capacity' => 4,
                'size' => 100,
                'amenities' => [
                    RoomAmenity::WIFI,
                    RoomAmenity::SMART_TV,
                    RoomAmenity::MINI_BAR,
                    RoomAmenity::AIR_CONDITIONING,
                    RoomAmenity::COFFEE_MACHINE,
                    RoomAmenity::SAFE,
                    RoomAmenity::BALCONY,
                    RoomAmenity::JACUZZI,
                    RoomAmenity::PREMIUM_TOILETRIES,
                    RoomAmenity::CITY_VIEW,
                ],
            ],
        ];

        try {
            DB::beginTransaction();

            // Prepare room type data for bulk insert
            $roomTypeInsertData = collect($roomTypesData)->map(function ($data) {
                return [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price_per_night' => $data['price_per_night'],
                    'capacity' => $data['capacity'],
                    'size' => $data['size'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Bulk insert room types
            RoomType::insert($roomTypeInsertData);

            // Get all room types indexed by name
            $roomTypes = RoomType::all()->keyBy('name');

            // Get all amenities indexed by name
            $amenities = Amenity::all()->keyBy('name');

            // Prepare pivot table data for bulk insert
            $pivotData = $this->preparePivotData(
                $roomTypesData,
                $roomTypes,
                $amenities
            );

            // Bulk insert into pivot table
            DB::table('amenity_room_type')->insert($pivotData);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to seed room types: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Prepare pivot table data for bulk insert.
     */
    private function preparePivotData(array $roomTypesData, Collection $roomTypes, Collection $amenities): array
    {
        $pivotData = [];
        $now = now();

        foreach ($roomTypesData as $data) {
            $roomTypeId = $roomTypes->get($data['name'])->id;

            foreach ($data['amenities'] as $amenity) {
                $amenityName = $amenity->value;

                if (! $amenities->has($amenityName)) {
                    Log::warning("Amenity not found: {$amenityName}");

                    continue;
                }

                $pivotData[] = [
                    'amenity_id' => $amenities->get($amenityName)->id,
                    'room_type_id' => $roomTypeId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        return $pivotData;
    }
}
