<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Data\EventData;

class CalendarWidget extends FullCalendarWidget
{
    // Widget configuration
    protected static string $heading = 'Booking Calendar';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getViewData(): array
    {
        return [
            'config' => [
                'headerToolbar' => [
                    'left' => 'prev,next today',
                    'center' => 'title',
                    'right' => 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                ],
                'initialView' => 'dayGridMonth',
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
                }',
            ],
        ];
    }

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        // Get date range from the fetch info
        $start = Carbon::parse($fetchInfo['start']);
        $end = Carbon::parse($fetchInfo['end']);

        // Query bookings that fall within this date range
        $bookings = Booking::query()
            ->where(function (Builder $query) use ($start, $end) {
                $query->whereBetween('check_in', [$start, $end])
                    ->orWhereBetween('check_out', [$start, $end])
                    ->orWhere(function (Builder $subQuery) use ($start, $end) {
                        $subQuery->where('check_in', '<=', $start)
                            ->where('check_out', '>=', $end);
                    });
            })
            ->with(['customer', 'room', 'roomType'])
            ->get();

        // Map bookings to calendar events
        return $bookings->map(function (Booking $booking) {
            $roomTypeLabel = $booking->roomType ? $booking->roomType->name : 'Unknown';
            $roomLabel = $booking->room ? "Room {$booking->room->room_number}" : '';
            $customerName = $booking->customer ? $booking->customer->name : 'Guest';

            return EventData::make()
                ->id($booking->id)
                ->title("{$roomLabel} - {$customerName}")
                ->start($booking->check_in)
                ->end($booking->check_out)
                ->allDay(true)
                ->backgroundColor($this->getStatusColor($booking->status))
                ->borderColor($this->getStatusColor($booking->status, true))
                ->textColor('#ffffff')
                ->extendedProps([
                    'customer_name' => $customerName,
                    'room_type' => $roomTypeLabel,
                    'room_number' => $roomLabel,
                    'total_price' => $booking->total_price,
                    'status' => $booking->status ? $booking->status->label() : 'Unknown',
                    'payment_status' => $booking->payment_status ? $booking->payment_status->label() : 'Unknown',
                    'tooltip' => "$roomLabel • $customerName • {$booking->status->label()}",
                ])
                ->toArray();
        })->toArray();
    }

    /**
     * Get color for booking status
     *
     * @param mixed $status The booking status
     * @param bool $darker Whether to return a darker shade (for borders)
     * @return string The hex color code
     */
    protected function getStatusColor($status, bool $darker = false): string
    {
        if (!$status) {
            return '#6B7280'; // Gray for unknown status
        }

        $colors = [
            'confirmed' => $darker ? '#059669' : '#10B981', // Green
            'pending' => $darker ? '#D97706' : '#F59E0B',   // Amber
            'cancelled' => $darker ? '#DC2626' : '#EF4444', // Red
            'completed' => $darker ? '#2563EB' : '#3B82F6', // Blue
            'default' => $darker ? '#4B5563' : '#6B7280',   // Gray
        ];

        return $colors[$status->value] ?? $colors['default'];
    }
}
