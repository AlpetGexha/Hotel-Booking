<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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
     * Scope a query to only include available rooms.
     */
    public function scopeAvailable($query, Carbon $checkIn = null, Carbon $checkOut = null)
    {
        $query->where('is_available', true);

        if ($checkIn && $checkOut) {
            $query->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    // Check if existing booking overlaps with requested dates
                    $q->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut])
                        ->orWhere(function ($innerQuery) use ($checkIn, $checkOut) {
                            // Or if requested dates are within an existing booking
                            $innerQuery->where('check_in', '<=', $checkIn)
                                ->where('check_out', '>=', $checkOut);
                        });
                });
            });
        }

        return $query;
    }
}
