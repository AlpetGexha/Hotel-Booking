<x-layout>
    <x-slot:title>Search For Room</x-slot:title>

    <!-- Search Results Header Card -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md mb-6">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Available Room Types</h1>

            <div class="mt-2 flex flex-wrap items-center gap-4 text-slate-600 dark:text-slate-400">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ Carbon\Carbon::parse($check_in_date)->format('M d') }} -
                        {{ Carbon\Carbon::parse($check_out_date)->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ $guests }} {{ Str::plural('guest', $guests) }} for {{ $nights }}
                        {{ Str::plural('night', $nights) }}</span>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div x-data="{ showFilters: false }" class="p-6 bg-slate-50 dark:bg-slate-900/50">
            <button @click="showFilters = !showFilters"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-md font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Modify Search
            </button>

            <!-- Expanded Form -->
            <div x-show="showFilters" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                <form method="GET" action="{{ route('search.rooms') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Check-in Date -->
                    <div>
                        <label for="check_in_date"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Check-in
                            Date</label>
                        <input type="date" id="check_in_date" name="check_in_date" value="{{ $check_in_date }}"
                            min="{{ now()->toDateString() }}"
                            class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Check-out Date -->
                    <div>
                        <label for="check_out_date"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Check-out
                            Date</label>
                        <input type="date" id="check_out_date" name="check_out_date" value="{{ $check_out_date }}"
                            min="{{ Carbon\Carbon::parse($check_in_date)->addDay()->toDateString() }}"
                            class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Guests -->
                    <div>
                        <label for="guests"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Guests</label>
                        <select id="guests" name="guests"
                            class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach (range(1, 10) as $i)
                                <option value="{{ $i }}" {{ $guests == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ Str::plural('Person', $i) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            @if ($roomTypes->count() > 0)
                Showing {{ $roomTypes->count() }} {{ Str::plural('room type', $roomTypes->count()) }}
            @else
                No room types available for your search criteria
            @endif
        </p>
    </div>

    <!-- Room Types Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($roomTypes as $roomType)
            <x-room-type-card :room-type="$roomType" :check-in-date="$check_in_date" :check-out-date="$check_out_date" :guests="$guests"
                :nights="$nights" />
        @empty
            <div class="col-span-3 bg-white dark:bg-slate-800 rounded-lg shadow-md p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-white">No rooms available</h3>
                <p class="mt-2 text-slate-600 dark:text-slate-400">
                    We couldn't find any rooms matching your criteria. Please try different dates, guest count, or
                    filters.
                </p>
                <div class="mt-6">
                    <a href="{{ route('home') }}"
                        class="text-indigo-600 dark:text-indigo-500 hover:text-indigo-500 dark:hover:text-indigo-400">
                        &larr; Back to search
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Return Link -->
    @if ($roomTypes->count() > 0)
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}"
                class="text-indigo-600 dark:text-indigo-500 hover:text-indigo-500 dark:hover:text-indigo-400 inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Return to Home
            </a>
        </div>
    @endif

</x-layout>
