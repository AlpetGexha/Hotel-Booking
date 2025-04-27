<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'floor',
        'room_type_id',
        'is_available',
        'notes',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Get the room type that owns the room.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get the bookings for this room.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope a query to only include rooms available for booking in a specific date range.
     */
    public function scopeAvailableForBooking($query, string $checkInDate, string $checkOutDate)
    {
        return $query->where('is_available', true)
            ->whereDoesntHave('bookings', function ($query) use ($checkInDate, $checkOutDate): void {
                $query->where(function ($q) use ($checkInDate, $checkOutDate): void {
                    // Check if existing booking overlaps with requested dates
                    $q->whereBetween('check_in', [$checkInDate, $checkOutDate])
                        ->orWhereBetween('check_out', [$checkInDate, $checkOutDate])
                        ->orWhere(function ($innerQuery) use ($checkInDate, $checkOutDate): void {
                            // Or if requested dates are within an existing booking
                            $innerQuery->where('check_in', '<=', $checkInDate)
                                ->where('check_out', '>=', $checkOutDate);
                        });
                });
            });
    }
}
