<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\RoomAmenity;
use App\Models\Amenity;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoomType>
 */
class RoomTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Standard',
                'Deluxe',
                'Suite',
                'Executive',
                'Penthouse',
                'Family Room',
                'Single Room',
                'Double Room',
            ]),
            'description' => fake()->paragraph(),
            'price_per_night' => fake()->randomFloat(2, 100, 1000),
            'capacity' => fake()->numberBetween(1, 6),
            'size' => function (array $attributes): int|float {
                // Set size based on room type and capacity
                $baseSize = match ($attributes['name']) {
                    'Standard', 'Single Room' => 15,
                    'Deluxe', 'Double Room' => 25,
                    'Suite', 'Family Room' => 35,
                    'Executive' => 45,
                    'Penthouse' => 65,
                    default => 20
                };

                // Add extra space based on capacity (5 sq meters per person above 1)
                $capacityAdjustment = ($attributes['capacity'] - 1) * 5;

                return $baseSize + $capacityAdjustment;
            },
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (RoomType $roomType): void {
            // Get a random subset of amenities (between 3 and 8)
            // Using a static cache to reduce database queries when creating multiple room types
            static $amenities = null;

            if ($amenities === null) {
                $amenities = Amenity::all(['id'])->pluck('id')->toArray();
            }

            // Randomly select a subset of amenity IDs
            $randomCount = fake()->numberBetween(3, min(8, count($amenities)));
            $selectedAmenityIds = collect($amenities)
                ->random($randomCount)
                ->toArray();

            // Attach the amenities to the room type (more efficient than loading full models)
            $roomType->amenities()->attach($selectedAmenityIds);
        });
    }

    /**
     * Indicate that the room type should have specific amenities.
     *
     * @param  array<int, RoomAmenity|string>  $amenities
     */
    public function withAmenities(array $amenities): static
    {
        return $this->afterCreating(function (RoomType $roomType) use ($amenities): void {
            // Convert RoomAmenity enum cases to their string values if necessary
            $amenityNames = collect($amenities)->map(fn ($amenity) => $amenity instanceof RoomAmenity ? $amenity->value : $amenity)->toArray();

            // Find amenities by name
            $amenityIds = Amenity::whereIn('name', $amenityNames)->pluck('id');

            // Attach the specific amenities
            $roomType->amenities()->attach($amenityIds);
        });
    }
}
