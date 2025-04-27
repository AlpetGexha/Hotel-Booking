<?php

declare(strict_types=1);

namespace App\Providers;

use Closure;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class SpatieMediaLibraryExtensionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->extendSpatieMediaLibraryImageColumn();
    }

    /**
     * Extend the SpatieMediaLibraryImageColumn class with custom functionality.
     */
    protected function extendSpatieMediaLibraryImageColumn(): void
    {
        SpatieMediaLibraryImageColumn::macro('cacheState', function (Closure $callback): array {
            /** @var SpatieMediaLibraryImageColumn $this */
            $record = $this->getRecord();

            if (! $record instanceof Model || ! $record->exists) {
                return $callback();
            }

            $recordKey = $record->getKey();
            $recordClass = get_class($record);
            $column = $this->getName();
            $collection = $this->getCollection() ?? 'default';
            $collectionString = is_string($collection) ? $collection : 'all_collections';

            // Create a unique cache key based on the model, ID, column, and collection
            $cacheKey = "spatie_media_{$recordClass}_{$recordKey}_{$column}_{$collectionString}";

            // If in local environment, use a shorter cache time for easier debugging
            $cacheDuration = app()->environment('local')
                ? now()->addMinutes(5)
                : now()->addHours(6);

            return Cache::remember(
                $cacheKey,
                $cacheDuration,
                $callback
            );
        });
    }
}
