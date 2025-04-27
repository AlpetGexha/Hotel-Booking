<?php

namespace App\Http\Controllers;

use App\Actions\AvaibleRoomAction;
use App\Actions\CreateBookingAction;
use App\Actions\CreateMultipleBookingsAction;
use App\Actions\StoreBookingAction;
use App\Actions\StoreMultipleBookingsAction;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StoreMultipleBookingsRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\PricingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    protected PricingService $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    /**
     * Show the booking form.
     */
    public function create(Request $request, CreateBookingAction $action): View
    {
        // Validate required parameters
        $validated = $request->validate([
            'room_type_id' => ['required', 'exists:room_types,id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        // Calculate nights and total price using PricingService
        $roomType = RoomType::findOrFail($validated['room_type_id']);
        $nights = $this->pricingService->getNightsCount(
            $validated['check_in_date'],
            $validated['check_out_date']
        );

        $totalPrice = $this->pricingService->calculateTotalPrice(
            $roomType,
            $validated['check_in_date'],
            $validated['check_out_date']
        );

        // Pass pre-calculated values to the action
        $viewData = $action->handle($validated, $totalPrice, $nights);

        return view('bookings.create', $viewData);
    }

    /**
     * Store a new booking.
     */
    public function store(StoreBookingRequest $request, StoreBookingAction $bookingAction)
    {
        // Calculate the total price using PricingService
        $roomType = RoomType::findOrFail($request->room_type_id);
        
        $totalPrice = $this->pricingService->calculateTotalPrice(
            $roomType,
            $request->check_in_date,
            $request->check_out_date
        );

        // Pass request and pre-calculated price to the action
        $booking = $bookingAction->handle($request, $totalPrice);

        if (!$booking) {
            return back()
                ->withErrors(['room_availability' => 'No rooms of this type are available for the selected dates'])
                ->withInput();
        }

        return redirect()->route('bookings.confirmation', $booking)
            ->with('success', 'Your booking has been confirmed!');
    }

    /**
     * Show booking confirmation.
     */
    public function confirmation(Booking $booking)
    {
        return view('bookings.confirmation', [
            'booking' => $booking,
        ]);
    }

    /**
     * Show the form for creating multiple room bookings.
     */
    public function createMultipleRooms(Request $request, CreateMultipleBookingsAction $action): View
    {
        // Validate required parameters
        $validated = $request->validate([
            'room_ids' => ['required', 'array', 'min:1'],
            'room_ids.*' => ['required', 'exists:rooms,id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        // Calculate nights using PricingService
        $nights = $this->pricingService->getNightsCount(
            $validated['check_in_date'],
            $validated['check_out_date']
        );

        // Load rooms
        $rooms = Room::with('roomType.amenities')
            ->whereIn('id', $validated['room_ids'])
            ->get();

        // Calculate individual and total prices
        $roomPrices = [];
        $totalPrice = 0;

        foreach ($rooms as $room) {
            $price = $this->pricingService->calculateTotalPrice(
                $room->roomType,
                $validated['check_in_date'],
                $validated['check_out_date']
            );

            $roomPrices[$room->id] = [
                'room' => $room,
                'price_per_night' => $room->roomType->price_per_night,
                'total_price' => $price,
            ];

            $totalPrice += $price;
        }

        // Pass pre-calculated values to the action
        $viewData = $action->handle($validated, $roomPrices, $nights, $totalPrice);

        return view('bookings.create-multiple', $viewData);
    }

    /**
     * Store multiple room bookings.
     */
    public function storeMultipleRooms(StoreMultipleBookingsRequest $request, StoreMultipleBookingsAction $action)
    {
        // Load rooms
        $rooms = Room::whereIn('id', $request->room_ids)->with('roomType')->get();

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

        if (!$bookings) {
            return back()
                ->withErrors(['message' => 'An error occurred while processing your booking.'])
                ->withInput();
        }

        return redirect()->route('bookings.confirmation', [
            'booking' => $bookings->first(),
            'multiple' => true,
            'booking_count' => $bookings->count(),
        ]);
    }
}
