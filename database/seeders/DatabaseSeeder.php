<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // First seed the room types
        $this->call(RoomTypeSeeder::class);

        // Then seed the rooms (which depend on room types)
        $this->call(RoomSeeder::class);

        // You can add more seeders here as your application grows
        // \App\Models\User::factory(10)->create();
    }
}
