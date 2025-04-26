<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'icon',
        'description',
    ];

    /**
     * The room types that have this amenity.
     */
    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoomType::class);
    }
}
