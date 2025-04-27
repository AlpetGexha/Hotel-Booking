<?php

namespace App\Filament\Pages;

use App\Settings\HotelSettings as SettingsHotelSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Notifications\Notification;

class HotelSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Hotel Settings';
    protected static ?string $title = 'Hotel Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 10;

    protected static string $settings = SettingsHotelSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tab::make('Hotel Information')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Section::make('General Information')
                                    ->description('Basic information about your hotel')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Hotel Name')
                                            ->required()
                                            ->maxLength(100),

                                        TextInput::make('email')
                                            ->label('Email Address')
                                            ->email()
                                            ->required()
                                            ->maxLength(100),

                                        TextInput::make('phone')
                                            ->label('Phone Number')
                                            ->tel()
                                            ->required()
                                            ->maxLength(20),

                                        Textarea::make('address')
                                            ->label('Hotel Address')
                                            ->required()
                                            ->rows(3)
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Tab::make('Social Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Social Media Links')
                                    ->description('Your hotel\'s social media profiles')
                                    ->schema([
                                        TextInput::make('hotel.facebook')
                                        // tha
                                            ->label('Facebook URL')
                                            ->url()
                                            // ->prefix('https://')
                                            ->maxLength(100),

                                        TextInput::make('hotel.instagram')
                                            ->label('Instagram URL')
                                            ->url()
                                            // ->prefix('https://')
                                            ->maxLength(100),

                                        TextInput::make('hotel.twitter')
                                            ->label('Twitter/X URL')
                                            ->url()
                                            // ->prefix('https://')
                                            ->maxLength(100),

                                        TextInput::make('hotel.linkedin')
                                            ->label('LinkedIn URL')
                                            ->url()
                                            // ->prefix('https://')
                                            ->maxLength(100),

                                        TextInput::make('hotel.youtube')
                                            ->label('YouTube URL')
                                            ->url()
                                            // ->prefix('https://')
                                            ->maxLength(100),

                                        TextInput::make('hotel.cv')
                                            ->label('CV URL')
                                            ->url()
                                            // ->prefix('https://')
                                            ->maxLength(100),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
