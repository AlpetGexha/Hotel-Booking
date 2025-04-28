<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class MyBookingsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $now = Carbon::now();

        // Get bookings where the user is directly linked through a customer record
        $userBookings = Booking::query()
            ->whereHas('customer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['roomType', 'room'])
            ->get();

        // Get bookings for others that this user created
        // This is a bit complex as we need to find customers created by this user
        // but not directly linked to them
        $otherBookings = Booking::query()
            ->whereDoesntHave('customer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('customer', function ($query) use ($user) {
                $query->where('email', $user->email); // Assuming email is used to track bookings made by the same user
            })
            ->with(['roomType', 'room'])
            ->get();

        // Combine all bookings
        $allBookings = $userBookings->concat($otherBookings);

        // Split into upcoming and past bookings
        $upcomingBookings = $allBookings->filter(function ($booking) use ($now) {
            return Carbon::parse($booking->check_out)->isAfter($now);
        })->sortBy('check_in');

        $pastBookings = $allBookings->filter(function ($booking) use ($now) {
            return Carbon::parse($booking->check_out)->isBefore($now);
        })->sortByDesc('check_out');

        return view('bookings.my-bookings', [
            'upcomingBookings' => $upcomingBookings,
            'pastBookings' => $pastBookings,
        ]);
    }
}
