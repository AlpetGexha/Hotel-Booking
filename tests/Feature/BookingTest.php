<?php

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('booking create page loads successfully', function () {
    $response = $this->get(route('bookings.create'));

    $response->assertStatus(200);
});

test('booking can be stored successfully', function () {
    // Create test data
    $roomType = RoomType::factory()->create();
    $room = Room::factory()->create([
        'room_type_id' => $roomType->id,
        'status' => \App\Enum\RoomStatus::Available,
    ]);

    $bookingData = [
        'check_in' => now()->addDay()->format('Y-m-d'),
        'check_out' => now()->addDays(3)->format('Y-m-d'),
        'room_id' => $room->id,
        'guests' => 2,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'phone' => '1234567890',
        'special_requests' => 'Early check-in if possible',
    ];

    $response = $this->post(route('bookings.store'), $bookingData);

    $booking = Booking::first();

    $this->assertNotNull($booking);
    $response->assertRedirect(route('bookings.confirmation', $booking));

    $this->assertDatabaseHas('customers', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
    ]);

    $this->assertDatabaseHas('bookings', [
        'room_id' => $room->id,
        'check_in' => $bookingData['check_in'],
        'check_out' => $bookingData['check_out'],
        'guests' => 2,
    ]);
});

test('booking store validates required fields', function () {
    $response = $this->post(route('bookings.store'), []);

    $response->assertSessionHasErrors([
        'check_in', 'check_out', 'room_id', 'guests',
        'first_name', 'last_name', 'email', 'phone'
    ]);
});

test('booking confirmation page loads with valid booking', function () {
    // Create a booking
    $customer = Customer::factory()->create();
    $roomType = RoomType::factory()->create();
    $room = Room::factory()->create(['room_type_id' => $roomType->id]);
    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'room_id' => $room->id,
    ]);

    $response = $this->get(route('bookings.confirmation', $booking));

    $response->assertStatus(200);
    $response->assertViewHas('booking', $booking);
});

test('booking confirmation page returns 404 for non-existent booking', function () {
    $response = $this->get(route('bookings.confirmation', 999));

    $response->assertStatus(404);
});

test('multiple rooms booking create page loads successfully', function () {
    $response = $this->get(route('bookings.create-multiple'));

    $response->assertStatus(200);
});

test('multiple rooms booking can be stored successfully', function () {
    // Create test data
    $roomType1 = RoomType::factory()->create();
    $roomType2 = RoomType::factory()->create();
    $room1 = Room::factory()->create([
        'room_type_id' => $roomType1->id,
        'status' => \App\Enum\RoomStatus::Available,
    ]);
    $room2 = Room::factory()->create([
        'room_type_id' => $roomType2->id,
        'status' => \App\Enum\RoomStatus::Available,
    ]);

    $bookingData = [
        'check_in' => now()->addDay()->format('Y-m-d'),
        'check_out' => now()->addDays(3)->format('Y-m-d'),
        'room_ids' => [$room1->id, $room2->id],
        'guests' => [2, 1],
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'phone' => '0987654321',
        'special_requests' => 'Adjoining rooms if possible',
    ];

    $response = $this->post(route('bookings.store-multiple'), $bookingData);

    // Check if bookings were created
    $this->assertEquals(2, Booking::count());

    // Check if customer was created
    $this->assertDatabaseHas('customers', [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
    ]);

    // We expect a redirect to the confirmation page of the first booking
    $booking = Booking::first();
    $response->assertRedirect(route('bookings.confirmation', $booking));
});

test('multiple rooms booking validates required fields', function () {
    $response = $this->post(route('bookings.store-multiple'), []);

    $response->assertSessionHasErrors([
        'check_in', 'check_out', 'room_ids', 'guests',
        'first_name', 'last_name', 'email', 'phone'
    ]);
});

test('multiple rooms booking validates each room has a guest count', function () {
    // Create test data
    $roomType1 = RoomType::factory()->create();
    $roomType2 = RoomType::factory()->create();
    $room1 = Room::factory()->create(['room_type_id' => $roomType1->id]);
    $room2 = Room::factory()->create(['room_type_id' => $roomType2->id]);

    $bookingData = [
        'check_in' => now()->addDay()->format('Y-m-d'),
        'check_out' => now()->addDays(3)->format('Y-m-d'),
        'room_ids' => [$room1->id, $room2->id],
        'guests' => [2], // Missing guest count for second room
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@example.com',
        'phone' => '0987654321',
    ];

    $response = $this->post(route('bookings.store-multiple'), $bookingData);

    $response->assertSessionHasErrors(['guests']);
});
