<?php

declare(strict_types=1);

use App\Http\Controllers\AmenitiesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SearchRoomsController;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome')->name('home');
Route::get('search-rooms', SearchRoomsController::class)->name('search.rooms');

// Pages
Route::view('contact', 'contact')->name('contact');
Route::get('amenities', [AmenitiesController::class, 'index'])->name('amenities');
Route::get('rooms', [RoomController::class, 'index'])->name('rooms');


// Booking routes
Route::get('booking', [BookingController::class, 'create'])->name('bookings.create');
Route::post('booking', [BookingController::class, 'store'])->name('bookings.store');
Route::get('booking/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('bookings.confirmation');

// Multiple room booking routes
Route::get('booking/multiple', [BookingController::class, 'createMultipleRooms'])->name('bookings.create-multiple');
Route::post('booking/multiple', [BookingController::class, 'storeMultipleRooms'])->name('bookings.store-multiple');

// Rooms route

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
//     Volt::route('settings/password', 'settings.password')->name('settings.password');
//     Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
// });

// require __DIR__ . '/auth.php';
