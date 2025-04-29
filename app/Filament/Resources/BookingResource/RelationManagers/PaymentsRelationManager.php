<?php

declare(strict_types=1);

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Enum\PaymentMethod;
use App\Enum\PaymentStatus;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

final class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Payment History';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // We don't need to edit payments from this relation manager
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y • h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PaymentMethod ? $state->label() : 'Unknown')
                    ->colors([
                        'success' => fn ($state) => $state instanceof PaymentMethod && $state === PaymentMethod::CREDIT_CARD,
                        'warning' => fn ($state) => $state instanceof PaymentMethod && $state === PaymentMethod::CASH,
                        'primary' => fn ($state) => $state instanceof PaymentMethod && $state === PaymentMethod::BANK_TRANSFER,
                    ]),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PaymentStatus ? $state->label() : 'Unknown')
                    ->colors([
                        'success' => fn ($state) => $state instanceof PaymentStatus && $state === PaymentStatus::PAID,
                        'warning' => fn ($state) => $state instanceof PaymentStatus && $state === PaymentStatus::PENDING,
                        'danger' => fn ($state) => $state instanceof PaymentStatus && $state === PaymentStatus::FAILED,
                    ]),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(30)
                    ->placeholder('No notes'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // No filters needed for this simple relation manager
            ])
            ->headerActions([
                // Don't allow creating payments directly from here
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions needed
            ])
            ->emptyStateHeading('No payment records')
            ->emptyStateDescription('This booking has no payment records yet.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }
}
