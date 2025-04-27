<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\SearchRoomsAction;
use App\Http\Requests\SearchRoomsRequest;
use Illuminate\Contracts\View\View;

final class SearchRoomsController extends Controller
{
    /**
     * Handle the search rooms request.
     */
    public function __invoke(SearchRoomsRequest $request, SearchRoomsAction $action): View
    {
        // Get search results using the action class by passing the request object directly
        $searchResults = $action->handle($request);

        // Extract the room types from the search results
        $roomTypes = $searchResults['roomTypes'];
        $alternatives = $searchResults['alternatives'] ?? null;
        $suggestion = $searchResults['suggestion'] ?? null;

        // Calculate nights
        $nights = $request->getNightsCount();

        // Return a view with results
        return view('rooms.search-results', [
            'roomTypes' => $roomTypes,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'guests' => $request->guests,
            'nights' => $nights,
            'alternatives' => $alternatives,
            'suggestion' => $suggestion,
        ]);
    }
}
