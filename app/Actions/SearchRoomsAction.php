<?php

namespace App\Actions;

use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class SearchRoomsAction
{
    /**
     * Execute the room search.
     */
    public function handle(
        string $checkInDate,
        string $checkOutDate,
        int $guests,
        ?array $amenityIds = null,
        ?float $minPrice = null,
        ?float $maxPrice = null
    ): Collection {
        // Parse dates
        $checkIn = Carbon::parse($checkInDate)->startOfDay();
        $checkOut = Carbon::parse($checkOutDate)->startOfDay();

        // Create a cache key for this search
        $cacheKey = $this->generateCacheKey($checkIn, $checkOut, $guests, $amenityIds, $minPrice, $maxPrice);

        // Cache the results for 10 minutes to improve performance
        // return Cache::remember($cacheKey, now()->addMinutes(10), function () use (
        //     $checkIn,
        //     $checkOut,
        //     $guests,
        //     $amenityIds,
        //     $minPrice,
        //     $maxPrice
        // ) {
        return $this->findAvailableRoomTypes($checkIn, $checkOut, $guests, $amenityIds, $minPrice, $maxPrice);
        // });
    }

    /**
     * Find available room types based on the given criteria.
     */
    private function findAvailableRoomTypes(
        Carbon $checkIn,
        Carbon $checkOut,
        int $guests,
        ?array $amenityIds,
        ?float $minPrice,
        ?float $maxPrice
    ): Collection {
        // Start with a base query for room types
        $query = RoomType::query()
            ->with(['amenities']) // Eager load amenities to avoid N+1 problems
            ->where('capacity', '>=', $guests);

        // Apply price filters if provided
        if ($minPrice !== null) {
            $query->where('price_per_night', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price_per_night', '<=', $maxPrice);
        }

        // Filter by amenities if provided
        if ($amenityIds && count($amenityIds) > 0) {
            $query->whereHas('amenities', function (Builder $query) use ($amenityIds) {
                $query->whereIn('amenities.id', $amenityIds);
            }, '=', count($amenityIds)); // Ensures all selected amenities are present
        }

        // Get room types that have available rooms for the date range
        // This assumes a Room model with a scope for availability
        $query->whereHas('rooms', function (Builder $query) use ($checkIn, $checkOut) {
            $query->availableForBooking($checkIn, $checkOut);
        });

        // Get the results with eager loaded relationships
        return $query->orderBy('price_per_night')->get();
    }

    /**
     * Generate a unique cache key for the search parameters.
     */
    private function generateCacheKey(
        Carbon $checkIn,
        Carbon $checkOut,
        int $guests,
        ?array $amenityIds,
        ?float $minPrice,
        ?float $maxPrice
    ): string {
        $amenitiesKey = $amenityIds ? implode('-', $amenityIds) : 'none';
        $minPriceKey = $minPrice ?? 'min';
        $maxPriceKey = $maxPrice ?? 'max';

        return "room-search:{$checkIn->format('Y-m-d')}:{$checkOut->format('Y-m-d')}:{$guests}:{$amenitiesKey}:{$minPriceKey}:{$maxPriceKey}";
    }
}
