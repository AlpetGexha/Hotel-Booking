<?php

declare(strict_types=1);

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Bookings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form schema removed as we don't need it (no create/edit functionality)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
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
                    ->state(function ($record): int {
                        return Carbon::parse($record->check_in)->diffInDays(Carbon::parse($record->check_out));
                    }),
                Tables\Columns\TextColumn::make('room.room_number')
                    ->label('Room Number'),
                Tables\Columns\TextColumn::make('roomType.name')
                    ->label('Room Type'),
                Tables\Columns\TextColumn::make('guests')
                    ->label('Guests'),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('check_in', 'desc')
            ->filters([
                Tables\Filters\Filter::make('upcoming')
                    ->query(fn (Builder $query): Builder => $query->where('check_in', '>=', now()))
                    ->label('Upcoming'),
                Tables\Filters\Filter::make('current')
                    ->query(fn (Builder $query): Builder => $query->where('check_in', '<=', now())->where('check_out', '>=', now()))
                    ->label('Current'),
                Tables\Filters\Filter::make('past')
                    ->query(fn (Builder $query): Builder => $query->where('check_out', '<', now()))
                    ->label('Past'),
            ])
            ->headerActions([
                // Removed Create action
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Removed bulk actions
            ]);
    }
}
