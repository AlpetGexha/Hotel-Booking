<?php

declare(strict_types=1);

return [
    /*
     * Each settings class used in your application must be registered, you can
     * put them (manually) here.
     */
    'settings' => [
        \App\Settings\HotelSettings::class,
    ],

    /*
     * The path where the settings classes will be created.
     */
    'settings_path' => app_path('Settings'),

    /*
     * In these directories settings migrations will be stored and ran when migrating.
     */
    'migrations_paths' => [
        database_path('settings'),
    ],

    /*
     * When no repository was set for a settings class the following repository
     * will be used for loading and saving settings.
     */
    'default_repository' => 'database',

    /*
     * Settings will be stored and loaded from these repositories.
     */
    'repositories' => [
        'database' => [
            'type' => Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository::class,
            'model' => null,
            'table' => 'settings',
            'connection' => null,
        ],
    ],

    /*
     * Caches are used to improve performance.
     */
    'cache' => [
        'enabled' => env('SETTINGS_CACHE_ENABLED', true),
        'store' => null,
        'prefix' => 'laravel_settings',
    ],

    /*
     * The contents of settings classes can be cached,
     * reducing lookup times and minimizing database queries.
     */
    'cache_path' => storage_path('app/settings.json'),
];
