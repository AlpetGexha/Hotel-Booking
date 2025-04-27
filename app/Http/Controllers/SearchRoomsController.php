<?php

namespace App\Http\Controllers;

use App\Actions\SearchRoomsAction;
use App\Http\Requests\SearchRoomsRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;

class SearchRoomsController extends Controller
{
    /**
     * Handle the search rooms request.
     */
    public function __invoke(SearchRoomsRequest $request): View
    {
        // Validate the request using the form request
        $validated = $request->validated();

        // Get search results using the action class
        $searchAction = App::make(SearchRoomsAction::class);

        $roomTypes = $searchAction->handle(
            $validated['check_in_date'],
            $validated['check_out_date'],
            $validated['guests'],
            $validated['amenities'] ?? null,
            $validated['price_min'] ?? null,
            $validated['price_max'] ?? null
        );

        // Calculate nights
        $nights = $request->getNightsCount();

        // Return a view with results
        return view('rooms.search-results', [
            'roomTypes' => $roomTypes,
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'guests' => $validated['guests'],
            'nights' => $nights,
        ]);
    }
}
