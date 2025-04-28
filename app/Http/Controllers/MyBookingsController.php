<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\FetchUserBookingsAction;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class MyBookingsController extends Controller
{

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, FetchUserBookingsAction $fetchUserBookingsAction): View
    {
        $user = $request->user();

        if (!$user) {
            return view('bookings.my-bookings', [
                'upcomingSelfBookings' => collect(),
                'upcomingOthersBookings' => collect(),
                'pastSelfBookings' => collect(),
                'pastOthersBookings' => collect(),
            ]);
        }

        $bookings = $fetchUserBookingsAction->handle($user);

        return view('bookings.my-bookings', $bookings);
    }
}
