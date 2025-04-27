<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create seed data for both current and future bookings

        // Get existing customer IDs for relationships
        $customerIds = Customer::pluck('id')->toArray();

        // Get existing room type IDs for relationships
        $roomTypeIds = RoomType::pluck('id')->toArray();

        if (empty($customerIds) || empty($roomTypeIds)) {
            $this->command->info('Skipping booking seeds: No customers or room types found.');
            return;
        }

        // Create 15 past bookings (checked out)
        Booking::factory()
            ->count(15)
            ->withAssignedRoom()
            ->create([
                'customer_id' => fn() => $customerIds[array_rand($customerIds)],
                'room_type_id' => fn() => $roomTypeIds[array_rand($roomTypeIds)],
                'check_in' => fn() => Carbon::now()->subDays(rand(30, 60)),
                'check_out' => fn() => Carbon::now()->subDays(rand(1, 29)),
            ]);

        // Create 20 current bookings (checked in, not checked out)
        Booking::factory()
            ->count(20)
            ->withAssignedRoom()
            ->create([
                'customer_id' => fn() => $customerIds[array_rand($customerIds)],
                'room_type_id' => fn() => $roomTypeIds[array_rand($roomTypeIds)],
                'check_in' => fn() => Carbon::now()->subDays(rand(1, 5)),
                'check_out' => fn() => Carbon::now()->addDays(rand(1, 5)),
            ]);

        // Create 30 future bookings
        Booking::factory()
            ->count(30)
            ->withAssignedRoom()
            ->create([
                'customer_id' => fn() => $customerIds[array_rand($customerIds)],
                'room_type_id' => fn() => $roomTypeIds[array_rand($roomTypeIds)],
                'check_in' => fn() => Carbon::now()->addDays(rand(7, 60)),
                'check_out' => fn() => Carbon::now()->addDays(rand(61, 100)),
            ]);
    }
}
