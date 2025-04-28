<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\View\View;

final class AmenitiesController extends Controller
{
    public function index(): View
    {
        $amenities = Amenity::all()->groupBy(function ($amenity, $key) {
            return $key % 8; // Group amenities into rows of 4 (for 2 rows)
        });

        return view('amenities', [
            'amenities' => $amenities,
        ]);
    }
}
