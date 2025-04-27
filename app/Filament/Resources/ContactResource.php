<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Customer Interactions';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('message')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('ip_address')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\IconColumn::make('media')
                    ->label('Has Attachments')
                    ->boolean()
                    ->state(fn (Contact $record): bool => $record->getMedia('attachments')->count() > 0),
                Tables\Columns\TextColumn::make('media_count')
                    ->label('Files')
                    ->counts('media')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Contact Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('subject')
                            ->weight(FontWeight::Bold)
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                        Infolists\Components\TextEntry::make('email')
                            ->icon('heroicon-m-envelope')
                            ->iconColor('primary')
                            ->url(fn (Contact $record): string => "mailto:{$record->email}")
                            ->openUrlInNewTab(),
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime()
                            ->icon('heroicon-m-calendar')
                            ->iconColor('gray'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Message')
                    ->schema([
                        Infolists\Components\TextEntry::make('message')
                            ->prose()
                            ->markdown(),
                    ]),

                Infolists\Components\Section::make('Attachments')
                    ->visible(fn (Contact $record): bool => $record->getMedia('attachments')->count() > 0)
                    ->schema([
                        Infolists\Components\Grid::make()
                            ->schema(function (Contact $record): array {
                                $mediaItems = $record->getMedia('attachments');
                                $schema = [];

                                foreach ($mediaItems as $index => $media) {
                                    $extension = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);

                                    $schema[] = Infolists\Components\Section::make("attachment_{$index}")
                                        ->hiddenLabel()
                                        ->schema([
                                            $isImage
                                                ? Infolists\Components\ImageEntry::make("media_{$index}")
                                                    ->label('Preview')
                                                    ->state($media->getUrl())
                                                    ->height('200px')
                                                    ->extraAttributes(['class' => 'rounded-md shadow-sm object-cover'])
                                                : Infolists\Components\TextEntry::make("media_placeholder_{$index}")
                                                    ->label('File Type')
                                                    ->view('filament.infolists.components.file-icon', [
                                                        'extension' => $extension,
                                                    ]),

                                            Infolists\Components\TextEntry::make("media_details_{$index}")
                                                ->label('File Details')
                                                ->view('filament.infolists.components.attachment-details', [
                                                    'media' => $media,
                                                    'extension' => $extension,
                                                    'fileSize' => $media->size / 1024 > 1024
                                                        ? round($media->size / 1024 / 1024, 2) . ' MB'
                                                        : round($media->size / 1024, 1) . ' KB',
                                                ]),
                                        ])
                                        ->columns(1);
                                }

                                return $schema;
                            }),
                    ]),

                Infolists\Components\Section::make('No Attachments')
                    ->visible(fn (Contact $record): bool => $record->getMedia('attachments')->count() === 0)
                    ->schema([
                        Infolists\Components\TextEntry::make('no_attachments')
                            ->label('Attachments')
                            ->state('No attachments were uploaded with this contact submission.')
                            ->color('gray'),
                    ]),

                Infolists\Components\Section::make('System Information')
                    ->collapsed()
                    ->schema([
                        Infolists\Components\TextEntry::make('ip_address')
                            ->label('IP Address')
                            ->copyable()
                            ->copyMessage('IP address copied!'),
                        Infolists\Components\TextEntry::make('id')
                            ->label('Contact ID'),
                    ])
                    ->columns(2),
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
            'index' => Pages\ListContacts::route('/'),
            'view' => Pages\ViewContact::route('/{record}'),
        ];
    }
}
