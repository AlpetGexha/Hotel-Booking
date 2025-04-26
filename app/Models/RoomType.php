<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_night',
        'capacity',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
    ];

    /**
     * Get the rooms for this room type.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }
}
