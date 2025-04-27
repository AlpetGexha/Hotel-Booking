<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\RoomAmenity;
use App\Models\Amenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class AmenitySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prepare data for bulk insert based on our RoomAmenity enum
        $amenities = collect(RoomAmenity::cases())->map(fn (RoomAmenity $amenity): array => [
            'name' => $amenity->value,
            'icon' => $amenity->getHeroicon(),
            'description' => $amenity->getDescription(),
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        // Insert all amenities in a single query for better performance
        Amenity::insert($amenities);
    }
}
