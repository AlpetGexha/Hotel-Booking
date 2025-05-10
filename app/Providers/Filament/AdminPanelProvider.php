<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Resources\BookingResource\Pages\CalendarBookings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

final class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $plugin = [
            'headerToolbar' => [
                // 'left' => 'prev,next today',
                // 'center' => 'title',
                // 'right' => 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
            ],
            'initialView' => 'resourceTimeline',
            'dayMaxEvents' => true,
            'eventTimeFormat' => [
                'hour' => '2-digit',
                'minute' => '2-digit',
                'meridiem' => 'short',
            ],
            'navLinks' => true,
            'eventClick' => 'function(info) {
                    const url = "/admin/bookings/" + info.event.id;
                    window.open(url, "_self");
                }',
            'eventDidMount' => 'function(info) {
                    const props = info.event.extendedProps;
                    if (props.tooltip) {
                        const tooltip = document.createElement("div");
                        tooltip.className = "calendar-event-tooltip";
                        tooltip.innerHTML = `
                            <div class="font-medium">${props.room_number}</div>
                            <div class="text-sm">Guest: ${props.customer_name}</div>
                            <div class="text-sm">Status: ${props.status}</div>
                            <div class="text-sm">Payment: ${props.payment_status}</div>
                            <div class="text-sm font-medium mt-1">$${Number(props.total_price).toFixed(2)}</div>
                        `;

                        document.body.appendChild(tooltip);

                        const mouseEnter = () => {
                            tooltip.style.display = "block";
                        };

                        const mouseLeave = () => {
                            tooltip.style.display = "none";
                        };

                        const mouseMove = (e) => {
                            tooltip.style.left = e.pageX + 10 + "px";
                            tooltip.style.top = e.pageY + 10 + "px";
                        };

                        info.el.addEventListener("mouseenter", mouseEnter);
                        info.el.addEventListener("mouseleave", mouseLeave);
                        info.el.addEventListener("mousemove", mouseMove);

                        tooltip.style.display = "none";
                    }
                }', ];

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName(config('app.name'))
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                CalendarBookings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            // ->plugin(
            //     FilamentFullCalendarPlugin::make()
            //         // ->schedulerLicenseKey()
            //         ->selectable()
            //         ->editable()
            //         // ->timezone()
            //         // ->locale()
            //         ->plugins([
            //             'resourceTimeline',
            //         ])
            //         ->config($plugin)
            // )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
