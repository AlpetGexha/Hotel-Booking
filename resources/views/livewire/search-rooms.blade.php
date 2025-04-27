<div
    class="w-full mx-auto"
    x-data="{
        showFilters: false,
        showResults: true,
        priceRange: {
            min: {{ $minPrice ?? 0 }},
            max: {{ $maxPrice ?? 1000 }}
        }
    }"
>
    <!-- Search Form Card -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md mb-6">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Find Your Perfect Room</h1>
            <p class="mt-2 text-slate-600 dark:text-slate-400">
                Search for available rooms based on your preferences
            </p>
        </div>

        <div class="p-6">
            <form wire:submit.prevent="search" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Check-in Date -->
                <div>
                    <label for="checkInDate" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Check-in Date
                    </label>
                    <input
                        type="date"
                        id="checkInDate"
                        wire:model.live="checkInDate"
                        min="{{ now()->format('Y-m-d') }}"
                        class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('checkInDate')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Check-out Date -->
                <div>
                    <label for="checkOutDate" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Check-out Date
                    </label>
                    <input
                        type="date"
                        id="checkOutDate"
                        wire:model.live="checkOutDate"
                        min="{{ Carbon\Carbon::parse($checkInDate)->addDay()->format('Y-m-d') }}"
                        class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('checkOutDate')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guests -->
                <div>
                    <label for="guests" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Guests
                    </label>
                    <select
                        id="guests"
                        wire:model.live="guests"
                        class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach (range(1, 10) as $i)
                            <option value="{{ $i }}">{{ $i }} {{ Str::plural('Person', $i) }}</option>
                        @endforeach
                    </select>
                    @error('guests')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Search Button -->
                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:ring-offset-slate-800"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search Rooms
                    </button>
                </div>
            </form>

            <!-- Advanced Filters Toggle -->
            <div class="mt-6 flex justify-center">
                <button
                    @click="showFilters = !showFilters"
                    type="button"
                    class="inline-flex items-center px-3 py-2 border border-slate-300 dark:border-slate-600 shadow-sm text-sm leading-4 font-medium rounded-md text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:ring-offset-slate-800"
                >
                    <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'">Show Filters</span>
                    <svg x-show="!showFilters" xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="showFilters" xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            </div>

            <!-- Advanced Filters -->
            <div
                x-show="showFilters"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700"
            >
                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Advanced Filters</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-4">
                            Price Range: $<span x-text="priceRange.min"></span> - $<span x-text="priceRange.max"></span> per night
                        </label>

                        <div class="relative mt-3">
                            <input
                                type="range"
                                min="0"
                                max="500"
                                step="10"
                                x-model="priceRange.min"
                                wire:model.live.debounce.500ms="minPrice"
                                class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer"
                            >
                            <input
                                type="range"
                                min="500"
                                max="1000"
                                step="10"
                                x-model="priceRange.max"
                                wire:model.live.debounce.500ms="maxPrice"
                                class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer mt-4"
                            >
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-4">
                            Amenities
                        </label>

                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($amenities as $amenity)
                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        id="amenity-{{ $amenity->id }}"
                                        value="{{ $amenity->id }}"
                                        wire:model.live="selectedAmenities"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600 rounded"
                                    >
                                    <label for="amenity-{{ $amenity->id }}" class="ml-2 text-sm text-slate-600 dark:text-slate-400">
                                        {{ $amenity->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('selectedAmenities')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading wire:target="search, checkInDate, checkOutDate, guests, selectedAmenities, minPrice, maxPrice" class="my-6">
        <div class="flex justify-center items-center">
            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-indigo-600 font-medium">Loading rooms...</p>
        </div>
    </div>

    <!-- Results Section -->
    <div
        x-show="showResults"
        class="mt-6"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
    >
        <!-- Results Count -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">
                Available Room Types
                <span class="text-indigo-600">
                    {{ Carbon\Carbon::parse($checkInDate)->format('M d') }} - {{ Carbon\Carbon::parse($checkOutDate)->format('M d, Y') }}
                </span>
            </h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">
                @if ($roomTypes && $roomTypes->count() > 0)
                    Showing {{ $roomTypes->count() }} {{ Str::plural('room type', $roomTypes->count()) }}
                    for {{ $guests }} {{ Str::plural('guest', $guests) }} · {{ $nights }} {{ Str::plural('night', $nights) }}
                @else
                    No room types available for your search criteria
                @endif
            </p>
        </div>

        <!-- Room Types Grid -->
        @if ($roomTypes && $roomTypes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($roomTypes as $roomType)
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden">
                        <!-- Room Image -->
                        <div class="h-48 bg-slate-300 dark:bg-slate-700">
                            <img
                                src="{{ $roomType->thumbnailUrl }}"
                                alt="{{ $roomType->name }}"
                                class="w-full h-full object-cover"
                            >
                        </div>

                        <!-- Room Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">{{ $roomType->name }}</h3>

                            <div class="flex items-center mb-4">
                                <!-- Room Capacity -->
                                <div class="flex items-center text-slate-600 dark:text-slate-400 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ $roomType->capacity }} {{ Str::plural('Person', $roomType->capacity) }}</span>
                                </div>

                                <!-- Room Size -->
                                @if ($roomType->size)
                                <div class="flex items-center text-slate-600 dark:text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                                    </svg>
                                    <span>{{ $roomType->formatted_size }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Amenities -->
                            @if ($roomType->amenities->count() > 0)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Amenities</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($roomType->amenities->take(4) as $amenity)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">
                                                {{ $amenity->name }}
                                            </span>
                                        @endforeach

                                        @if ($roomType->amenities->count() > 4)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300">
                                                +{{ $roomType->amenities->count() - 4 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Description -->
                            <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-2">
                                {{ $roomType->description ?? 'Experience comfort and luxury in our ' . Str::lower($roomType->name) . ' with premium amenities and beautiful views.' }}
                            </p>

                            <!-- Price & Book Button -->
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                                <div>
                                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-500">${{ number_format($roomType->price_per_night, 0) }}</span>
                                    <span class="text-slate-600 dark:text-slate-400 text-sm">/night</span>

                                    <p class="text-slate-600 dark:text-slate-400 text-sm">
                                        ${{ number_format($roomType->price_per_night * $nights, 0) }} total
                                    </p>
                                </div>

                                <a
                                    href="{{ route('room-types.show', $roomType) }}?check_in_date={{ $checkInDate }}&check_out_date={{ $checkOutDate }}&guests={{ $guests }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:ring-offset-slate-800"
                                >
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Results Found -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-white">No rooms available</h3>
                <p class="mt-2 text-slate-600 dark:text-slate-400">
                    We couldn't find any rooms matching your criteria. Please try different dates, guest count, or filters.
                </p>
            </div>
        @endif
    </div>
</div>
