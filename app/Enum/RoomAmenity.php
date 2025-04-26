<?php

namespace App\Enum;

use Filament\Support\Contracts\HasIcon;

enum RoomAmenity: string implements HasIcon
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
     * Get the Heroicon name for this amenity
     *
     * @return string
     */
    public function getHeroicon(): string
    {
        return match ($this) {
            self::WIFI => 'heroicon-o-wifi',
            self::TV, self::SMART_TV => 'heroicon-o-tv',
            self::MINI_BAR => 'heroicon-o-beaker',
            self::AIR_CONDITIONING => 'heroicon-o-cpu-chip',
            self::COFFEE_MACHINE => 'heroicon-o-fire',
            self::SAFE => 'heroicon-o-lock-closed',
            self::DESK => 'heroicon-o-computer-desktop',
            self::SOFA => 'heroicon-o-home',
            self::DINING_AREA => 'heroicon-o-cake',
            self::PRIVATE_BATHROOM, self::BATHTUB => 'heroicon-o-plus',
            self::SHOWER => 'heroicon-o-cloud',
            self::BALCONY => 'heroicon-o-sun',
            self::OCEAN_VIEW => 'heroicon-o-arrow-path-rounded-square',
            self::CITY_VIEW => 'heroicon-o-building-office',
            self::PREMIUM_TOILETRIES => 'heroicon-o-sparkles',
            self::EXTRA_BEDS => 'heroicon-o-queue-list',
            self::CHILDREN_AMENITIES => 'heroicon-o-user-group',
            self::JACUZZI => 'heroicon-o-fire',
            default => 'heroicon-o-check',
        };
    }

    /**
     * Get the icon name for this amenity
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->getHeroicon();
    }

    /**
     * Get a description for this amenity
     *
     * @return string
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::WIFI => 'High-speed wireless internet access',
            self::TV => 'Standard flat-screen television',
            self::SMART_TV => 'Smart TV with streaming capabilities',
            self::MINI_BAR => 'In-room refreshments and snacks',
            self::AIR_CONDITIONING => 'Individual climate control system',
            self::COFFEE_MACHINE => 'In-room coffee and tea making facilities',
            self::SAFE => 'In-room safe for valuables',
            self::DESK => 'Work desk with appropriate lighting',
            self::SOFA => 'Comfortable seating area',
            self::DINING_AREA => 'Dedicated space for in-room dining',
            self::PRIVATE_BATHROOM => 'Ensuite bathroom facilities',
            self::BATHTUB => 'Bathtub for relaxation',
            self::SHOWER => 'Modern shower facilities',
            self::BALCONY => 'Private outdoor space',
            self::OCEAN_VIEW => 'Room with a view of the ocean',
            self::CITY_VIEW => 'Room with a view of the city skyline',
            self::PREMIUM_TOILETRIES => 'High-quality bath and body products',
            self::EXTRA_BEDS => 'Additional sleeping arrangements available',
            self::CHILDREN_AMENITIES => 'Child-friendly facilities and items',
            self::JACUZZI => 'Private jacuzzi or hot tub',
        };
    }

    /**
     * Get all enum values as an array suitable for select options
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $amenity) => [$amenity->value => $amenity->value])
            ->toArray();
    }
}
