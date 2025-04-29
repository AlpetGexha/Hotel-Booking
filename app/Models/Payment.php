<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\PaymentMethod;
use App\Enum\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'method',
        'reference',
        'paymentable_id',
        'paymentable_type',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_method' => PaymentMethod::class,
        'status' => PaymentStatus::class,
    ];

    /**
     * Get the parent paymentable model (Booking).
     */
    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }
}
