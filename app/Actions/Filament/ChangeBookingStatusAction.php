<?php

declare(strict_types=1);

namespace App\Actions\Filament;

use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Infolists\Components\Actions\Action as InfolistAction;
use Filament\Tables\Actions\Action as TableAction;

final class ChangeBookingStatusAction
{
    public static function make(?string $actionFor = 'table')
    {
        $name = 'changeStatus';

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
            ->label('Update Status')
            ->color('primary')
            ->icon('heroicon-o-arrow-path')
            ->form([
                \Filament\Forms\Components\Select::make('status')
                    ->label('Booking Status')
                    ->options(\App\Enum\BookingStatus::class)
                    ->required(),
            ])
            ->action(function (Booking $record, array $data): void {
                $record->status = $data['status'];
                $record->save();

                \Filament\Notifications\Notification::make()
                    ->title('Booking status updated')
                    ->success()
                    ->send();

                // $this->notify('success', 'Booking status updated successfully');
            });
    }
}
