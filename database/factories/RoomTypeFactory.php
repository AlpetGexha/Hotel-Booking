<?php

namespace Database\Factories;

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
            'name' => fake()->unique()->randomElement(['Standard', 'Deluxe', 'Suite', 'Executive', 'Penthouse', 'Family Room', 'Single Room', 'Double Room']),
            'description' => fake()->paragraph(),
            'price_per_night' => fake()->randomFloat(2, 100, 1000),
            'capacity' => fake()->numberBetween(1, 6),
            'amenities' => fake()->randomElements(['WiFi', 'TV', 'Mini Bar', 'Air Conditioning', 'Desk', 'Safe', 'Bathtub', 'Shower', 'Balcony', 'Ocean View'], fake()->numberBetween(3, 6)),
        ];
    }
}
