<?php

declare(strict_types=1);

namespace App\Actions\Filament;

use App\Enum\PaymentStatus;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Infolists\Components\Actions\Action as InfolistAction;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Support\Facades\DB;

final class ProcessPaymentAction
{
    public static function make(?string $actionFor = 'table')
    {

        $name = 'processPayment';

        if ($actionFor === 'table') {
            $action = TableAction::make($name);
        }
        if ($actionFor === 'infolist') {
            $action = InfolistAction::make($name);
            // ->size('md')
        }
        if ($actionFor === 'action') {
            $action = Action::make($name);
        }

        return $action
            ->label('Process Payment')
            // ->visible(fn(Booking $record): bool => $record->getBalanceDue() > 0)
            ->color('success')
            ->icon('heroicon-o-banknotes')
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
                DB::transaction(function () use ($record, $data) {
                    $record->total_payed = ($record->total_payed ?? 0) + $data['amount'];
                    $record->payment_method = $data['payment_method'];

                    // Update payment status based on payment amount
                    if ($record->isFullyPaid()) {
                        $record->payment_status = PaymentStatus::PAID;
                    } elseif ($record->total_payed > 0) {
                        $record->payment_status = PaymentStatus::PARTIAL;
                    } else {
                        $record->payment_status = PaymentStatus::PENDING;
                    }

                    // Create a new payment record
                    $record->payments()->create([
                        'amount' => $data['amount'],
                        'method' => $record->payment_method,
                        'status' => $data['amount'] < 0 ? PaymentStatus::REFUNDED : PaymentStatus::PAID,
                        'reference' => 'Payment for Booking #' . $record->id,
                    ]);

                    $record->save();

                    \Filament\Notifications\Notification::make()
                        ->title('Payment processed successfully')
                        ->success()
                        ->send();
                });
            });
    }
}
