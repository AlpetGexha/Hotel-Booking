<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Rooms';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'room_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Room Information')
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
                            ->placeholder('1')
                            ->helperText('Floor number where the room is located'),

                        Forms\Components\Select::make('room_type_id')
                            ->relationship('roomType', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('price_per_night')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$'),
                                Forms\Components\TextInput::make('capacity')
                                    ->required()
                                    ->numeric(),
                            ])
                            ->helperText('The type of room which determines amenities and pricing'),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Notes')
                    ->schema([
                        Forms\Components\Toggle::make('is_available')
                            ->required()
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->helperText('Whether this room is available for booking'),

                        Forms\Components\Textarea::make('notes')
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('room_number')
            ->columns([
                Tables\Columns\TextColumn::make('room_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('floor')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('roomType.name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('roomType.price_per_night')
                    ->money('USD')
                    ->sortable()
                    ->label('Price'),

                Tables\Columns\IconColumn::make('is_available')
                    ->boolean()
                    ->sortable()
                    ->label('Available')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('room_type')
                    ->relationship('roomType', 'name')
                    ->preload()
                    ->multiple()
                    ->label('Room Type'),

                Tables\Filters\Filter::make('floor')
                    ->form([
                        Forms\Components\TextInput::make('floor')
                            ->label('Floor')
                            ->numeric(),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['floor'],
                        fn (Builder $query, $floor): Builder => $query->where('floor', $floor),
                    )),

                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Availability')
                    ->placeholder('All Rooms')
                    ->trueLabel('Available Rooms')
                    ->falseLabel('Unavailable Rooms')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_availability')
                    ->label(fn (Room $record): string => $record->is_available ? 'Mark Unavailable' : 'Mark Available')
                    ->icon(fn (Room $record): string => $record->is_available ? 'heroicon-m-x-mark' : 'heroicon-m-check')
                    ->color(fn (Room $record): string => $record->is_available ? 'danger' : 'success')
                    ->action(function (Room $record): void {
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
