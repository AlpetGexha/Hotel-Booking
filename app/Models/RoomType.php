<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
final class RoomType extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

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
     * Register media collections and conversions.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('room_photo')
            ->useFallbackUrl('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80')
            ->singleFile();
    }

    /**
     * Register media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(420)
            ->height(280)
            ->sharpen(10)
            ->useFallbackUrl('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80')
            ->nonQueued();

        $this->addMediaConversion('small')
            ->width(320)
            ->height(240)
            ->nonQueued();
    }

    /**
     * Get the room photo URL.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('room_photo', 'thumbnail');
    }

    /**
     * Get the thumbnail URL with fallback to placeholder.
     */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('room_photo', 'thumbnail');
    }

    // https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80

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
