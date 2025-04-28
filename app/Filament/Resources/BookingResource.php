<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                    ->state(function (Booking $record): int|float {
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
                    ->color(fn (Booking $record): string => $record->status->color())
                    ->formatStateUsing(fn (Booking $record): string => $record->status->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (Booking $record): ?string => $record->payment_status?->color())
                    ->formatStateUsing(fn (Booking $record): ?string => $record->payment_status?->label())
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->icon(fn (Booking $record): ?string => $record->payment_method?->icon())
                    ->color(fn (Booking $record): ?string => $record->payment_method?->color())
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('changeStatus')
                    ->label('Change Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Booking Status')
                            ->options(\App\Enum\BookingStatus::class)
                            ->required()
                    ])
                    ->action(function (Booking $record, array $data): void {
                        $record->status = $data['status'];
                        $record->save();

                        \Filament\Notifications\Notification::make()
                            ->title('Booking status updated')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('processPayment')
                    ->label('Process Payment')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Payment Amount')
                            ->default(fn (Booking $record) => $record->getBalanceDue())
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        \Filament\Forms\Components\Select::make('payment_method')
                            ->label('Payment Method')
                            ->options(\App\Enum\PaymentMethod::class)
                            ->default(fn (Booking $record) => $record->payment_method ?? \App\Enum\PaymentMethod::CASH)
                            ->required(),
                    ])
                    ->action(function (Booking $record, array $data): void {
                        // Update payment information
                        $record->total_payed = ($record->total_payed ?? 0) + $data['amount'];
                        $record->payment_method = $data['payment_method'];

                        // Update payment status based on payment amount
                        if ($record->isFullyPaid()) {
                            $record->payment_status = \App\Enum\PaymentStatus::PAID;
                        } elseif ($record->total_payed > 0) {
                            $record->payment_status = \App\Enum\PaymentStatus::PARTIAL;
                        } else {
                            $record->payment_status = \App\Enum\PaymentStatus::PENDING;
                        }

                        $record->save();

                        \Filament\Notifications\Notification::make()
                            ->title('Payment processed successfully')
                            ->success()
                            ->send();
                    }),
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
        ];
    }
}
