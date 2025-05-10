<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Widgets\CalendarWidget;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Panel;
use Illuminate\Support\ServiceProvider;

final class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Amber,
            'success' => Color::Green,
            'warning' => Color::Amber,
            'indigo' => Color::Indigo,
            'purple' => Color::Purple,
        ]);
    }
}
