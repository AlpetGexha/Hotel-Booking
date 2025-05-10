<?php

declare(strict_types=1);

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Filament\Widgets\CalendarWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\Page;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Enums\MaxWidth;

class CalendarBookings extends Page
{
    protected static string $resource = BookingResource::class;

    protected static ?string $slug = 'booking/calendar';

    protected static string $view = 'filament.resources.booking-resource.pages.calendar-bookings';

    protected static ?string $title = 'Calendar View';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function getNavigationGroup(): ?string
    {
        return BookingResource::getNavigationGroup();
    }

    public static function getNavigationLabel(): string
    {
        return 'Calendar View';
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('list')
                ->label('List View')
                ->url(fn(): string => BookingResource::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-table-cells')
                ->button(),

            \Filament\Actions\Action::make('refresh')
                ->label('Refresh Calendar')
                ->action(fn() => $this->refreshPage())
                ->color('warning')
                ->icon('heroicon-o-arrow-path')
                ->button(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
    // Override layout to make calendar widget full-width
    public function getColumns(): int | array
    {
        // Return a custom layout that makes the calendar take up the full width
        return 1;
    }
}
