<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\RoomTypeResource\Pages;
use App\Filament\Resources\RoomTypeResource\RelationManagers;
use App\Models\Amenity;
use App\Models\RoomType;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class RoomTypeResource extends Resource
{
    protected static ?string $model = RoomType::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Room Types';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Room Type Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('price_per_night')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01),

                        Forms\Components\TextInput::make('capacity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->default(2),

                        Forms\Components\TextInput::make('size')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(500)
                            ->suffix('m²')
                            ->hint('Room size in square meters')
                            ->helperText('Enter the room size in square meters')
                            ->placeholder('e.g., 25'),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        SpatieMediaLibraryFileUpload::make('room_photo')
                            ->collection('room_photo')
                            ->maxFiles(1)
                            ->image()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('3:2')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('800')
                            ->helperText('Upload a high-quality photo of the room.')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Amenities')
                    ->schema([
                        Forms\Components\CheckboxList::make('amenities')
                            ->relationship('amenities', 'name')
                            ->options(function() {
                                return Amenity::query()->pluck('name', 'id');
                            })
                            ->columns(3)
                            ->searchable()
                            ->gridDirection('row')
                            ->helperText('Select the amenities available in this room type'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                SpatieMediaLibraryImageColumn::make('room_photo')
                    ->collection('room_photo')
                    ->conversion('thumbnail')
                    ->square()
                    ->defaultImageUrl('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=420&h=280&q=80')
                    ->label('Photo'),

                Tables\Columns\TextColumn::make('price_per_night')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->sortable()
                    ->label('Max Guests'),

                Tables\Columns\TextColumn::make('size')
                    ->numeric()
                    ->sortable()
                    ->suffix(' m²')
                    ->placeholder('Not specified')
                    ->label('Room Size'),

                Tables\Columns\TextColumn::make('amenities_count')
                    ->counts('amenities')
                    ->label('# Amenities')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rooms_count')
                    ->counts('rooms')
                    ->label('# Rooms')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('price_range')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('price_from')
                                    ->label('Min price')
                                    ->numeric()
                                    ->placeholder('Min price'),
                                Forms\Components\TextInput::make('price_to')
                                    ->label('Max price')
                                    ->numeric()
                                    ->placeholder('Max price'),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['price_from'],
                                fn (Builder $query, $price): Builder => $query->where('price_per_night', '>=', $price),
                            )
                            ->when(
                                $data['price_to'],
                                fn (Builder $query, $price): Builder => $query->where('price_per_night', '<=', $price),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['price_from'] ?? null) {
                            $indicators['price_from'] = 'Min price: $' . number_format($data['price_from']);
                        }

                        if ($data['price_to'] ?? null) {
                            $indicators['price_to'] = 'Max price: $' . number_format($data['price_to']);
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RoomsRelationManager::class,
            RelationManagers\AmenitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomTypes::route('/'),
            'create' => Pages\CreateRoomType::route('/create'),
            'edit' => Pages\EditRoomType::route('/{record}/edit'),
        ];
    }
}
