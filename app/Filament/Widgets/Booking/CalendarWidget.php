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
    // public $model = Booking::class;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

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
