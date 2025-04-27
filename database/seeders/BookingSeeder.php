<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::factory(5)
            ->create();

        Booking::factory(20)
            ->withAssignedRoom()
            ->create();
    }
}
