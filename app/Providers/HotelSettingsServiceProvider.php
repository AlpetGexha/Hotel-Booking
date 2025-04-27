<?php

namespace App\Providers;

use App\Settings\HotelSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
        } catch (Throwable $th) {
            logger()->info('Hotel settings not available: ' . $th->getMessage());
        }
    }
}
