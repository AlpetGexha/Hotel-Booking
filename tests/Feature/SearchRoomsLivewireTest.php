<?php

use App\Livewire\SearchRooms;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('search rooms livewire component can be rendered', function () {
    Livewire::test(SearchRooms::class)
        ->assertViewIs('livewire.search-rooms');
});

test('search rooms component can filter by check-in and check-out dates', function () {
    // Create test room types and rooms
    $roomType = RoomType::factory()->create();
    $room = Room::factory()->create([
        'room_type_id' => $roomType->id,
        'status' => \App\Enum\RoomStatus::Available,
    ]);

    $checkIn = now()->addDay()->format('Y-m-d');
    $checkOut = now()->addDays(3)->format('Y-m-d');

    Livewire::test(SearchRooms::class)
        ->set('checkIn', $checkIn)
        ->set('checkOut', $checkOut)
        ->set('guests', 2)
        ->call('search')
        ->assertSuccessful();
});

test('search rooms component validates inputs', function () {
    Livewire::test(SearchRooms::class)
        ->set('checkIn', '')
        ->set('checkOut', '')
        ->set('guests', 0)
        ->call('search')
        ->assertHasErrors(['checkIn', 'checkOut', 'guests']);
});

test('search rooms component handles invalid date ranges', function () {
    $checkIn = now()->addDays(5)->format('Y-m-d');
    $checkOut = now()->addDays(2)->format('Y-m-d'); // Before check-in

    Livewire::test(SearchRooms::class)
        ->set('checkIn', $checkIn)
        ->set('checkOut', $checkOut)
        ->set('guests', 2)
        ->call('search')
        ->assertHasErrors(['checkOut']);
});
