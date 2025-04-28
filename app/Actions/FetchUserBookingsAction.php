<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class FetchUserBookingsAction
{
    public function handle(User $user): array
    {
        // Early return if no user
        if (!$user) {
            return $this->emptyBookingsResult();
        }
        
        // Use optimized queries with eager loading to prevent N+1 issues
        return [
            // Self bookings - using the forUser local scope
            'upcomingSelfBookings' => $this->getSelfBookings($user, true),
            'pastSelfBookings' => $this->getSelfBookings($user, false),
            
            // Bookings for others - using the bookedByUserForOthers local scope
            'upcomingOthersBookings' => $this->getBookingsForOthers($user, true),
            'pastOthersBookings' => $this->getBookingsForOthers($user, false),
        ];
    }
    
    /**
     * Get bookings where the user is the customer
     */
    private function getSelfBookings(User $user, bool $upcoming): Collection
    {
        return Booking::query()
            ->forUser($user)
            ->when($upcoming, fn(Builder $q) => $q->upcoming(), fn(Builder $q) => $q->past())
            ->with(['roomType', 'room', 'customer'])
            ->orderBy($upcoming ? 'check_in' : 'check_out', $upcoming ? 'asc' : 'desc')
            ->get();
    }
    
    /**
     * Get bookings made by user for other people
     */
    private function getBookingsForOthers(User $user, bool $upcoming): Collection
    {
        return Booking::query()
            ->bookedByUserForOthers($user)
            ->when($upcoming, fn(Builder $q) => $q->upcoming(), fn(Builder $q) => $q->past())
            ->with(['roomType', 'room', 'customer', 'booker'])
            ->orderBy($upcoming ? 'check_in' : 'check_out', $upcoming ? 'asc' : 'desc')
            ->get();
    }
    
    private function emptyBookingsResult(): array
    {
        return [
            'upcomingSelfBookings' => collect(),
            'upcomingOthersBookings' => collect(),
            'pastSelfBookings' => collect(),
            'pastOthersBookings' => collect(),
        ];
    }
}
