<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

final class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_type_id',
        'room_id',
        'customer_id',
        'booker_id',
        'guests',
        'check_in',
        'check_out',
        'total_price',
        'total_payed',
        'special_requests',
        'status',
        'payment_method',
        'payment_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'total_price' => 'decimal:2',
        'total_payed' => 'decimal:2',
        'status' => BookingStatus::class,
        'payment_method' => \App\Enum\PaymentMethod::class,
        'payment_status' => \App\Enum\PaymentStatus::class,
    ];

    /**
     * Get the customer that owns the booking.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the room type that owns the booking.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get the room that owns the booking.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user who booked this reservation (if booked for someone else).
     */
    public function booker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booker_id');
    }

    /**
     * Scope a query to only include bookings for a specific user.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->whereHas('customer', fn (Builder $q) => $q->where('user_id', $user->id));
    }

    /**
     * Scope a query to only include bookings made by a user for others.
     */
    public function scopeBookedByUserForOthers(Builder $query, User $user): Builder
    {
        return $query->where('booker_id', $user->id);
    }

    /**
     * Scope a query to only include upcoming bookings.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('check_out', '>=', Carbon::now());
    }

    /**
     * Scope a query to only include past bookings.
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('check_out', '<', Carbon::now());
    }

    /**
     * Scope a query to only include current stays.
     */
    public function scopeCurrentStay(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->where('check_in', '<=', $now)
            ->where('check_out', '>=', $now);
    }

    /**
     * Check if booking is a current stay.
     */
    public function isCurrentStay(): bool
    {
        $now = Carbon::now();

        return $this->check_in->lte($now) && $this->check_out->gte($now);
    }

    /**
     * Check if booking is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->check_in->gt(Carbon::now());
    }

    /**
     * Get all payments for this booking.
     */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    /**
     * Check if booking is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->total_payed >= $this->total_price;
    }

    /**
     * Get the balance due for this booking.
     */
    public function getBalanceDue(): float
    {
        $balance = $this->total_price - ($this->total_payed ?? 0);

        return max(0, $balance);
    }
}
