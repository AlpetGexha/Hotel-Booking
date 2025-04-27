<?php

namespace App\Http\Controllers;

use App\Actions\AvaibleRoomAction;
use App\Http\Requests\BookingFormRequest;
use App\Http\Requests\MultiRoomBookingRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
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

    /**
     * Show the form for creating multiple room bookings.
     */
    public function createMultipleRooms(Request $request): View
    {
        // Validate required parameters
        $validated = $request->validate([
            'room_ids' => ['required', 'array', 'min:1'],
            'room_ids.*' => ['required', 'exists:rooms,id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        // Load rooms with their room types
        $roomIds = $validated['room_ids'];
        $rooms = Room::with('roomType.amenities')
            ->whereIn('id', $roomIds)
            ->get();

        // Calculate nights and individual room prices
        $nights = $this->pricingService->getNightsCount(
            $validated['check_in_date'],
            $validated['check_out_date']
        );

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

        return view('bookings.create-multiple', [
            'rooms' => $rooms,
            'roomPrices' => $roomPrices,
            'checkInDate' => $validated['check_in_date'],
            'checkOutDate' => $validated['check_out_date'],
            'guests' => $validated['guests'],
            'nights' => $nights,
            'totalPrice' => $totalPrice,
        ]);
    }

    /**
     * Store multiple room bookings.
     */
    public function storeMultipleRooms(Request $request, AvaibleRoomAction $action)
    {
        // Validate request
        $validated = $request->validate([
            'room_ids' => ['required', 'array', 'min:1'],
            'room_ids.*' => ['required', 'exists:rooms,id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests' => ['required', 'integer', 'min:1', 'max:20'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'special_requests' => ['nullable', 'string'],
        ]);

        // Start DB transaction for multiple bookings
        try {
            DB::beginTransaction();

            // Find or create customer
            $customer = Customer::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    // 'last_name' => $validated['last_name'],
                    // 'phone' => $validated['phone'],
                ]
            );

            $bookings = [];
            $rooms = Room::whereIn('id', $validated['room_ids'])->with('roomType')->get();

            // Create a booking for each room
            foreach ($rooms as $room) {
                // Verify room availability again
                $availableRoom = $action->handle(
                    $room->room_type_id,
                    $validated['check_in_date'],
                    $validated['check_out_date']
                );

                if (!$availableRoom) {
                    throw new Exception("Room {$room->room_number} is no longer available.");
                }

                // Calculate pricing
                $totalPrice = $this->pricingService->calculateTotalPrice(
                    $room->roomType,
                    $validated['check_in_date'],
                    $validated['check_out_date']
                );

                // Create booking record
                $booking = Booking::create([
                    'room_id' => $room->id,
                    'room_type_id' => $room->room_type_id, // Add the room_type_id field
                    'customer_id' => $customer->id,
                    'check_in' => $validated['check_in_date'],
                    'check_out' => $validated['check_out_date'],
                    'guests' => min($validated['guests'], $room->roomType->capacity),
                    'total_price' => $totalPrice,
                    'special_requests' => $validated['special_requests'] ?? null,
                    // 'status' => 'confirmed',
                ]);

                $bookings[] = $booking;
            }

            DB::commit();

            // Redirect to confirmation page with the primary booking ID
            return redirect()->route('bookings.confirmation', [
                'booking' => $bookings[0],
                'multiple' => true,
                'booking_count' => count($bookings),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => $e->getMessage()])->withInput();
        }
    }
}
