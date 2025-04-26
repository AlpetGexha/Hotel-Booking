<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price_per_night
 * @property int $capacity
 * @property int|null $size Size in square meters
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_night',
        'capacity',
        'size',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'capacity' => 'integer',
        'size' => 'integer',
    ];

    /**
     * Get the rooms for this room type.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get the amenities for this room type.
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

    /**
     * Get the formatted size attribute.
     */
    protected function formattedSize(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->size ? "{$this->size} m²" : null,
        );
    }
}
