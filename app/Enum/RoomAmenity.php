<?php

namespace App\Enum;

enum RoomAmenity: string
{
    case WIFI = 'WiFi';
    case TV = 'TV';
    case SMART_TV = 'Smart TV';
    case MINI_BAR = 'Mini Bar';
    case AIR_CONDITIONING = 'Air Conditioning';
    case COFFEE_MACHINE = 'Coffee Machine';
    case SAFE = 'Safe';
    case DESK = 'Desk';
    case SOFA = 'Sofa';
    case DINING_AREA = 'Dining Area';
    case PRIVATE_BATHROOM = 'Private Bathroom';
    case BATHTUB = 'Bathtub';
    case SHOWER = 'Shower';
    case BALCONY = 'Balcony';
    case OCEAN_VIEW = 'Ocean View';
    case CITY_VIEW = 'City View';
    case PREMIUM_TOILETRIES = 'Premium Toiletries';
    case EXTRA_BEDS = 'Extra Beds';
    case CHILDREN_AMENITIES = 'Children Amenities';
    case JACUZZI = 'Jacuzzi';

    /**
     * Get all enum values as an array suitable for select options
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $amenity) => [$amenity->value => $amenity->value])
            ->toArray();
    }
}
