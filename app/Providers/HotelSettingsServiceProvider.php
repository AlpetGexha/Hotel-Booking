<?php

namespace App\Providers;

use App\Settings\HotelSettings;
use App\Settings\SocialSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class HotelSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register any bindings or services here if needed
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            $hotelSettings = app(HotelSettings::class);

            View::share('hotelSettings', $hotelSettings);
        } catch (\Throwable $th) {
            logger()->info('Hotel settings not available: ' . $th->getMessage());
        }
    }
}
