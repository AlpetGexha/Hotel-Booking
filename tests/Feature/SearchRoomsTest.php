<?php

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('search rooms page loads successfully', function () {
    $response = $this->get(route('search.rooms'));

    $response->assertStatus(200);
});

test('search rooms with query parameters returns filtered results', function () {
    // Create test room types and rooms
    $roomType = RoomType::factory()->create();
    $room = Room::factory()->create([
        'room_type_id' => $roomType->id,
        'status' => \App\Enum\RoomStatus::Available,
    ]);

    $response = $this->get(route('search.rooms', [
        'check_in' => now()->addDay()->format('Y-m-d'),
        'check_out' => now()->addDays(3)->format('Y-m-d'),
        'guests' => 2,
    ]));

    $response->assertStatus(200);
    // Add assertions based on your search results page
});

test('search rooms with invalid dates returns validation errors', function () {
    $response = $this->get(route('search.rooms', [
        'check_in' => now()->subDay()->format('Y-m-d'), // Past date
        'check_out' => now()->format('Y-m-d'),
        'guests' => 2,
    ]));

    $response->assertSessionHasErrors(['check_in']);
});

test('search rooms with check_out before check_in returns validation errors', function () {
    $response = $this->get(route('search.rooms', [
        'check_in' => now()->addDays(3)->format('Y-m-d'),
        'check_out' => now()->addDay()->format('Y-m-d'), // Before check-in
        'guests' => 2,
    ]));

    $response->assertSessionHasErrors(['check_out']);
});

test('search rooms with invalid guests count returns validation errors', function () {
    $response = $this->get(route('search.rooms', [
        'check_in' => now()->addDay()->format('Y-m-d'),
        'check_out' => now()->addDays(3)->format('Y-m-d'),
        'guests' => 0, // Invalid guest count
    ]));

    $response->assertSessionHasErrors(['guests']);
});
