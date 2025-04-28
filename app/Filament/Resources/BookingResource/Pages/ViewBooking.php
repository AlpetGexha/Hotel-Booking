<?php

declare(strict_types=1);

namespace App\Filament\Resources\BookingResource\Pages;

use App\Actions\Filament\ChangeBookingStatusAction;
use App\Actions\Filament\ProcessPaymentAction;
use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Carbon\Carbon;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Str;

final class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Group::make([
                    Section::make('Booking Status')
                        ->description('Current status of the booking')
                        ->columnSpan(1)
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('id')
                                        ->label('Booking Reference')
                                        ->formatStateUsing(fn (int $state): string => '#' . str_pad((string) $state, 5, '0', STR_PAD_LEFT))
                                        ->weight(FontWeight::Bold)
                                        ->color('primary')
                                        ->size(TextEntry\TextEntrySize::Large),

                                    TextEntry::make('created_at')
                                        ->label('Booked On')
                                        ->dateTime('M d, Y • h:i A')
                                        ->color('gray'),
                                ]),
                            TextEntry::make('status')
                                ->label('')
                                ->badge()
                                ->formatStateUsing(fn (Booking $record): string => $record->status->label())
                                ->color(fn (Booking $record): string => $record->status->getColor()),

                            Actions::make([
                                ChangeBookingStatusAction::make('infolist'),
                            ]),
                        ]),

                    Section::make('Stay Information')
                        ->description('Details about the stay')
                        ->icon('heroicon-o-calendar')
                        ->collapsible()
                        ->persistCollapsed(false)
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('check_in')
                                        ->label('Check In')
                                        ->date('l, F j, Y')
                                        ->icon('heroicon-o-arrow-right-circle')
                                        ->iconColor('success'),

                                    TextEntry::make('check_out')
                                        ->label('Check Out')
                                        ->date('l, F j, Y')
                                        ->icon('heroicon-o-arrow-left-circle')
                                        ->iconColor('danger'),

                                    TextEntry::make('nights')
                                        ->label('Duration')
                                        ->state(function (Booking $record): float {
                                            return Carbon::parse($record->check_in)->diffInDays(Carbon::parse($record->check_out));
                                        })
                                        ->suffix(function (int $state): string {
                                            return ' ' . Str::plural('night', $state);
                                        })
                                        ->icon('heroicon-o-clock'),

                                    TextEntry::make('guests')
                                        ->label('Guests')
                                        ->suffix(function (int $state): string {
                                            return ' ' . Str::plural('person', $state);
                                        })
                                        ->icon('heroicon-o-user-group'),
                                ]),

                            TextEntry::make('special_requests')
                                ->label('Special Requests')
                                ->placeholder('None')
                                ->columnSpanFull()
                                ->markdown()
                                ->prose(),
                        ]),

                    Section::make('Room Details')
                        ->description('Information about the booked room')
                        ->icon('heroicon-o-home')
                        ->collapsible()
                        ->persistCollapsed(false)
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('room.room_number')
                                        ->label('Room Number')
                                        ->icon('heroicon-o-key')
                                        ->weight(FontWeight::Bold),

                                    TextEntry::make('roomType.name')
                                        ->label('Room Type')
                                        ->icon('heroicon-o-building-office-2')
                                        ->weight(FontWeight::Medium),
                                ]),

                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('roomType.capacity')
                                        ->label('Capacity')
                                        ->suffix(function (int $state): string {
                                            return ' ' . Str::plural('person', $state);
                                        })
                                        ->color('gray'),

                                    TextEntry::make('roomType.size')
                                        ->label('Room Size')
                                        ->suffix(' m²')
                                        ->color('gray')
                                        ->placeholder('Not specified'),
                                ]),

                            ImageEntry::make('roomType.photo_url')
                                ->label('Room Photo')
                                ->disk('public')
                                ->height(150)
                                ->width('100%')

                                ->defaultImageUrl(function (Booking $record) {
                                    return $record->roomType?->photo_url ??
                                        asset('images/room-placeholder.jpg');
                                })
                                ->columnSpanFull(),

                            TextEntry::make('roomType.description')
                                ->label('Description')
                                ->columnSpanFull()
                                ->markdown()
                                ->prose()
                                ->placeholder('No description available.'),
                        ]),
                ])->columnSpan(['lg' => 2]),

                Group::make([
                    Section::make('Customer Information')
                        ->description('Details of the guest')
                        ->icon('heroicon-o-user')
                        ->collapsible()
                        ->persistCollapsed(false)
                        ->schema([
                            TextEntry::make('customer.name')
                                ->label('Guest Name')
                                ->weight(FontWeight::Bold)
                                ->icon('heroicon-o-user-circle')
                                ->size(TextEntry\TextEntrySize::Large),

                            TextEntry::make('customer.email')
                                ->label('Email Address')
                                ->icon('heroicon-o-envelope')
                                ->color('gray')
                                ->copyable()
                                ->url(fn (string $state): string => "mailto:{$state}"),

                            TextEntry::make('customer.phone')
                                ->label('Phone Number')
                                ->icon('heroicon-o-phone')
                                ->color('gray')
                                ->placeholder('Not provided')
                                ->copyable(),

                            TextEntry::make('booker.name')
                                ->label('Booked By')
                                ->visible(fn (?Booking $record): bool => $record?->booker_id !== null)
                                ->icon('heroicon-o-user')
                                ->color('gray')
                                ->placeholder('Self booking'),
                        ]),

                    Section::make('Payment Information')
                        ->description('Payment details')
                        ->icon('heroicon-o-banknotes')
                        ->collapsible()
                        ->persistCollapsed(false)
                        ->schema([
                            TextEntry::make('total_price')
                                ->label('Total Amount')
                                ->money('USD')
                                ->weight(FontWeight::Bold)
                                ->size(TextEntry\TextEntrySize::Large)
                                ->color('success'),

                            TextEntry::make('total_payed')
                                ->label('Amount Paid')
                                ->money('USD')
                                ->placeholder('$0.00')
                                ->weight(FontWeight::Medium),

                            TextEntry::make('balance_due')
                                ->label('Balance Due')
                                ->state(fn (Booking $record): float => $record->getBalanceDue())
                                ->money('USD')
                                ->weight(FontWeight::Bold)
                                ->color(fn (Booking $record): string => $record->getBalanceDue() > 0 ? 'danger' : 'success'),

                            TextEntry::make('payment_status')
                                ->label('Payment Status')
                                ->badge()
                                ->formatStateUsing(fn (Booking $record): string => $record->payment_status ? $record->payment_status->label() : 'Not Set'),

                            Grid::make(2)
                                ->schema([
                                    IconEntry::make('payment_method'),
                                    TextEntry::make('payment_method')
                                        ->state(fn (Booking $record): ?string => $record?->payment_method?->value)
                                        ->label('Payment Method'),
                                ]),

                            Actions::make([
                                ProcessPaymentAction::make('infolist'),
                            ]),
                        ]),
                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label('Back to bookings')
                ->url(fn () => BookingResource::getUrl())
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),

            ChangeBookingStatusAction::make('action'),
            ProcessPaymentAction::make('action'),
        ];
    }
}
