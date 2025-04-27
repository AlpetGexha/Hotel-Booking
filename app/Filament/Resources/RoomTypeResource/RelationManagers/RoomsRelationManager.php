<?php

declare(strict_types=1);

namespace App\Filament\Resources\RoomTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    protected static ?string $recordTitleAttribute = 'room_number';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('room_number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(10)
                    ->placeholder('101')
                    ->helperText('Room number should start with the floor number'),

                Forms\Components\TextInput::make('floor')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->placeholder('1'),

                Forms\Components\Toggle::make('is_available')
                    ->required()
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark'),

                Forms\Components\Textarea::make('notes')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('room_number')
            ->defaultSort('room_number')
            ->columns([
                Tables\Columns\TextColumn::make('room_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('floor')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_available')
                    ->boolean()
                    ->sortable()
                    ->label('Available')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Available')
                    ->trueLabel('Available Rooms')
                    ->falseLabel('Unavailable Rooms')
                    ->native(false),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Automatically set the room_type_id to the current room type
                        $data['room_type_id'] = $this->getOwnerRecord()->id;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('toggle_availability')
                    ->label(fn ($record): string => $record->is_available ? 'Mark Unavailable' : 'Mark Available')
                    ->icon(fn ($record): string => $record->is_available ? 'heroicon-m-x-mark' : 'heroicon-m-check')
                    ->color(fn ($record): string => $record->is_available ? 'danger' : 'success')
                    ->action(function ($record): void {
                        $record->update(['is_available' => ! $record->is_available]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_available')
                        ->label('Mark as Available')
                        ->icon('heroicon-m-check')
                        ->color('success')
                        ->action(fn (Builder $query) => $query->update(['is_available' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('mark_unavailable')
                        ->label('Mark as Unavailable')
                        ->icon('heroicon-m-x-mark')
                        ->color('danger')
                        ->action(fn (Builder $query) => $query->update(['is_available' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
