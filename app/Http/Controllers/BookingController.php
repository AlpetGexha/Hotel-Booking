<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateBookingAction;
use App\Actions\CreateMultipleBookingsAction;
use App\Actions\StoreBookingAction;
use App\Actions\StoreMultipleBookingsAction;
use App\Exceptions\BookingException;
use App\Http\Requests\BookingCreateRequest;
use App\Http\Requests\CreateMultipleBookingsRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StoreMultipleBookingsRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\PricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

final class BookingController extends Controller
{
    public function __construct(
        protected readonly PricingService $pricingService
    ) {}

    /**
     * Show the booking form.
     */
    public function create(BookingCreateRequest $request, CreateBookingAction $action): View
    {
        // Validate required parameters
        $request->validated();

        // Calculate nights and total price using PricingService
        $roomType = RoomType::query()
            ->findOrFail($request->room_type_id);
        $nights = $this->pricingService->getNightsCount(
            $request->check_in_date,
            $request->check_out_date
        );

        $totalPrice = $this->pricingService->calculateTotalPrice(
            $roomType,
            $request->check_in_date,
            $request->check_out_date
        );

        $viewData = $action->handle($request, $totalPrice, $nights);

        return view('bookings.create', $viewData);
    }

    /**
     * Store a new booking.
     */
    public function store(StoreBookingRequest $request, StoreBookingAction $action): RedirectResponse
    {
        try {
            // Calculate the total price using PricingService
            $roomType = RoomType::query()
                ->findOrFail($request->room_type_id);
            $totalPrice = $this->pricingService->calculateTotalPrice(
                $roomType,
                $request->check_in_date,
                $request->check_out_date
            );

            // Pass request and pre-calculated price to the action
            $booking = $action->handle($request, $totalPrice);

            return redirect()
                ->route('bookings.confirmation', $booking)
                ->with('success', 'Your booking has been confirmed!');
        } catch (BookingException $e) {
            return back()
                ->withErrors(['room_availability' => $e->getMessage()])
                ->withInput();
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withErrors(['message' => 'An unexpected error occurred. Please try again.' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show booking confirmation.
     */
    public function confirmation(Booking $booking): View
    {
        return view('bookings.confirmation', [
            'booking' => $booking,
        ]);
    }

    /**
     * Show the form for creating multiple room bookings.
     */
    public function createMultipleRooms(CreateMultipleBookingsRequest $request, CreateMultipleBookingsAction $action): View
    {
        $request->validated();

        $nights = $this->pricingService->getNightsCount(
            $request->check_in_date,
            $request->check_out_date
        );

        // Load rooms with eager loading to avoid N+1 query problem
        $rooms = Room::with('roomType.amenities')
            ->whereIn('id', $request->room_ids)
            ->get();

        // Calculate individual and total prices
        $roomPrices = [];
        $totalPrice = 0;

        foreach ($rooms as $room) {
            $price = $this->pricingService->calculateTotalPrice(
                $room->roomType,
                $request->check_in_date,
                $request->check_out_date
            );

            $roomPrices[$room->id] = [
                'room' => $room,
                'price_per_night' => $room->roomType->price_per_night,
                'total_price' => $price,
            ];

            $totalPrice += $price;
        }

        // Pass request and pre-calculated values to the action
        $viewData = $action->handle($request, $roomPrices, $nights, $totalPrice);

        return view('bookings.create-multiple', $viewData);
    }

    /**
     * Store multiple room bookings.
     */
    public function storeMultipleRooms(StoreMultipleBookingsRequest $request, StoreMultipleBookingsAction $action): RedirectResponse
    {
        try {
            // Load rooms with eager loading
            $rooms = Room::query()
                ->whereIn('id', $request->room_ids)
                ->with('roomType')
                ->get();

            // Calculate room prices
            $roomPrices = [];
            foreach ($rooms as $room) {
                $price = $this->pricingService->calculateTotalPrice(
                    $room->roomType,
                    $request->check_in_date,
                    $request->check_out_date
                );

                $roomPrices[$room->id] = [
                    'total_price' => $price,
                ];
            }

            // Pass request and pre-calculated prices to the action
            $bookings = $action->handle($request, $roomPrices);

            return redirect()->route('bookings.confirmation', [
                'booking' => $bookings->first(),
                'multiple' => true,
                'booking_count' => $bookings->count(),
            ])->with('success', 'Your multiple room booking has been confirmed!');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withErrors(['message' => 'An unexpected error occurred. Please try again.' . $e->getMessage()])
                ->withInput();
        }
    }
}
