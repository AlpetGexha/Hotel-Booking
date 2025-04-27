<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\AvaibleRoomAction;
use App\Actions\SearchRoomsAction;
use App\Models\Amenity;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

final class SearchRooms extends Component
{
    use WithPagination;

    /**
     * Search form inputs
     */
    public string $checkInDate;
    public string $checkOutDate;
    public int $guests = 2;
    public ?Collection $amenities = null;
    public array $selectedAmenities = [];
    public ?float $minPrice = null;
    public ?float $maxPrice = null;

    /**
     * Computed properties
     */
    public ?Collection $roomTypes = null;
    public int $nights = 1;
    public ?string $suggestion = null;
    public ?array $alternatives = null;

    /**
     * Component initialization.
     */
    public function mount(): void
    {
        // Set default dates if not provided
        if (! isset($this->checkInDate)) {
            $this->checkInDate = now()->format('Y-m-d');
        }

        if (! isset($this->checkOutDate)) {
            $this->checkOutDate = now()->addDay()->format('Y-m-d');
        }

        // Load amenities for filters
        $this->amenities = Amenity::orderBy('name')->get();

        // Calculate nights
        $this->calculateNights();

        // Perform initial search
        $this->search();
    }

    /**
     * Calculate the number of nights between check-in and check-out dates.
     */
    public function calculateNights(): void
    {
        $checkIn = Carbon::parse($this->checkInDate);
        $checkOut = Carbon::parse($this->checkOutDate);

        $this->nights = max(1, $checkIn->diffInDays($checkOut));
    }

    /**
     * Validate user input before searching.
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName, [
            'checkInDate' => ['required', 'date', 'after_or_equal:' . now()->format('Y-m-d')],
            'checkOutDate' => ['required', 'date', 'after:checkInDate'],
            'guests' => ['required', 'integer', 'min:1', 'max:10'],
            'selectedAmenities' => ['sometimes', 'array'],
            'selectedAmenities.*' => ['integer', 'exists:amenities,id'],
            'minPrice' => ['nullable', 'numeric', 'min:0'],
            'maxPrice' => ['nullable', 'numeric', 'gt:minPrice'],
        ]);

        // Recalculate nights when dates change
        if (in_array($propertyName, ['checkInDate', 'checkOutDate'])) {
            $this->calculateNights();
        }

        // Auto-search after validation passes
        $this->search();
    }

    /**
     * Execute the room search.
     */
    public function search(): void
    {
        $this->validate([
            'checkInDate' => ['required', 'date', 'after_or_equal:' . now()->format('Y-m-d')],
            'checkOutDate' => ['required', 'date', 'after:checkInDate'],
            'guests' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        // Reset suggestions and alternatives
        $this->suggestion = null;
        $this->alternatives = null;

        // Create instances with dependency injection
        $availableRoomAction = new AvaibleRoomAction;
        $searchAction = new SearchRoomsAction($availableRoomAction);

        // Get search results
        $searchResults = $searchAction->handle(
            $this->checkInDate,
            $this->checkOutDate,
            $this->guests,
            ! empty($this->selectedAmenities) ? $this->selectedAmenities : null,
            $this->minPrice,
            $this->maxPrice
        );

        // Extract results
        $this->roomTypes = $searchResults['roomTypes'];
        $this->alternatives = $searchResults['alternatives'];
        $this->suggestion = $searchResults['suggestion'];
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.search-rooms', [
            'roomTypes' => $this->roomTypes,
            'amenities' => $this->amenities,
            'nights' => $this->nights,
            'suggestion' => $this->suggestion,
            'alternatives' => $this->alternatives,
        ]);
    }
}
