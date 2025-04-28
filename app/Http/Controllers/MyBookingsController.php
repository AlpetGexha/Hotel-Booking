<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\FetchUserBookingsAction;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class MyBookingsController extends Controller
{
    public function __construct(
        private readonly FetchUserBookingsAction $fetchUserBookingsAction
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
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

        $bookings = $this->fetchUserBookingsAction->handle($user);

        return view('bookings.my-bookings', $bookings);
    }
}
