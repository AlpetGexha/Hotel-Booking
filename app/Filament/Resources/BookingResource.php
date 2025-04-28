<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Actions\Filament\ChangeBookingStatusAction;
use App\Actions\Filament\ProcessPaymentAction;
use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

final class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Bookings';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form fields removed as we don't need them (no create/edit functionality)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('check_in')
                    ->date(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Booking ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nights')
                    ->label('Nights')
                    ->state(function (Booking $record): float {
                        return Carbon::parse($record->check_in)->diffInDays(Carbon::parse($record->check_out));
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw("DATEDIFF(check_out, check_in) {$direction}");
                    }),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Room Number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('roomType.name')
                    ->label('Room Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('guests')
                    ->label('Guests')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (Booking $record): string => $record->status->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->formatStateUsing(fn (Booking $record): string => $record->payment_status?->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->formatStateUsing(fn (Booking $record): ?string => $record->payment_method?->label())
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('upcoming')
                    ->query(fn (Builder $query): Builder => $query->where('check_in', '>=', now()))
                    ->label('Upcoming Bookings'),
                Tables\Filters\Filter::make('current')
                    ->query(fn (Builder $query): Builder => $query->where('check_in', '<=', now())->where('check_out', '>=', now()))
                    ->label('Current Stays'),
                Tables\Filters\Filter::make('past')
                    ->query(fn (Builder $query): Builder => $query->where('check_out', '<', now()))
                    ->label('Past Bookings'),
            ])
            ->actions([
                ActionGroup::make([
                    ChangeBookingStatusAction::make('table'),
                    ProcessPaymentAction::make('table'),
                ]),
            ])
            ->bulkActions([
                // Removed bulk actions as per requirements
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'view' => Pages\ViewBooking::route('/{record}'),
        ];
    }
}
