<?php

namespace App\Http\Controllers;

use App\Actions\AvaibleRoomAction;
use App\Http\Requests\BookingFormRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\RoomType;
use App\Services\PricingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function create(Request $request): View
    {
        // Validate required parameters
        $validated = $request->validate([
            'room_type_id' => ['required', 'exists:room_types,id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        // Get the room type - make sure we're getting a single model instance, not a collection
        $roomTypeId = $validated['room_type_id'];
        $roomType = RoomType::find($roomTypeId);

        if (! $roomType) {
            abort(404, 'Room type not found');
        }

        // Load amenities relationship
        $roomType->load('amenities');

        // Calculate nights and total price
        $nights = $this->pricingService->getNightsCount(
            $validated['check_in_date'],
            $validated['check_out_date']
        );

        $totalPrice = $this->pricingService->calculateTotalPrice(
            $roomType,
            $validated['check_in_date'],
            $validated['check_out_date']
        );

        return view('bookings.create', [
            'roomType' => $roomType,
            'checkInDate' => $validated['check_in_date'],
            'checkOutDate' => $validated['check_out_date'],
            'guests' => $validated['guests'],
            'nights' => $nights,
            'totalPrice' => $totalPrice,
        ]);
    }

    /**
     * Store a new booking.
     */
    public function store(BookingFormRequest $request, AvaibleRoomAction $action)
    {
        // Get validated data
        $validated = $request->validated();

        // Get the room type
        $roomTypeId = $validated['room_type_id'];
        $roomType = RoomType::find($roomTypeId);

        if (! $roomType) {
            abort(404, 'Room type not found');
        }

        // Calculate price using the service
        $totalPrice = $this->pricingService->calculateTotalPrice(
            $roomType,
            $validated['check_in_date'],
            $validated['check_out_date']
        );
        // Find an available room for this room type within requested dates
        $availableRoom = $action->handle(
            $roomTypeId,
            $validated['check_in_date'],
            $validated['check_out_date']
        );

        if (! $availableRoom) {
            return back()
                ->withErrors(['room_availability' => 'No rooms of this type are available for the selected dates'])
                ->withInput();
        }

        // Use database transaction for consistency
        try {
            $booking = DB::transaction(function () use ($validated, $totalPrice, $availableRoom) {
                // Find or create customer
                $customer = Customer::firstOrCreate(
                    [
                        'email' => $validated['email'],
                    ],
                    [
                        'name' => $validated['name'],
                        // 'phone' => $validated['phone'],
                    ]
                );

                // Create booking
                return Booking::create([
                    'room_type_id' => $validated['room_type_id'],
                    'room_id' => $availableRoom->id,
                    'customer_id' => $customer->id,
                    'guests' => $validated['guests'],
                    'check_in' => $validated['check_in_date'],
                    'check_out' => $validated['check_out_date'],
                    'total_price' => $totalPrice,
                    'special_requests' => $validated['special_requests'] ?? null,
                ]);
            });

            // Redirect to booking confirmation page
            return redirect()->route('bookings.confirmation', $booking)->with('success', 'Your booking has been confirmed!');
        } catch (Exception $e) {
            return back()
                ->withErrors(['booking_error' => 'An error occurred while processing your booking. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Show booking confirmation.
     */
    public function confirmation(\App\Models\Booking $booking)
    {
        return view('bookings.confirmation', [
            'booking' => $booking,
        ]);
    }
}
