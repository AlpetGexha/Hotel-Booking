<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_number' => fake()->unique()->numberBetween(100, 999),
            'floor' => fake()->numberBetween(1, 10),
            'room_type_id' => RoomType::inRandomOrder()->first()->id ?? RoomType::factory(),
            'is_available' => fake()->boolean(90), // 90% chance of being available
            'notes' => fake()->optional(0.3)->sentence(), // 30% chance of having notes
        ];
    }
}
