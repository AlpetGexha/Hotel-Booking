<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->safeEmail(),
            'subject' => $this->faker->sentence(),
            'message' => $this->faker->paragraphs(3, true),
            'ip_address' => $this->faker->ipv4(),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fn (array $attributes) => $attributes['created_at'],
        ];
    }
}
