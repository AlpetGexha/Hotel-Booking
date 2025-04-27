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
            @if (is_countable($roomTypes) && count($roomTypes) > 0)
                Showing {{ count($roomTypes) }} {{ Str::plural('room type', count($roomTypes)) }}
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

    <!-- Alternative Suggestions Section -->
    @if (isset($suggestion) && isset($alternatives) &&
        ((isset($alternatives['larger']) && is_countable($alternatives['larger']) && count($alternatives['larger']) > 0) ||
        (isset($alternatives['multiple']) && is_countable($alternatives['multiple']) && count($alternatives['multiple']) > 0 && $guests > 4)))
        <div class="my-6 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-lg shadow-md p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3 w-full">
                    <h3 class="text-md font-medium text-amber-800 dark:text-amber-300">Alternative Options Available</h3>
                    <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                        <p>{{ $suggestion }}</p>
                    </div>

                    <!-- Alternatives Section -->
                    <div class="mt-4">
                        <!-- For larger rooms alternative -->
                        @if (isset($alternatives['larger']) && is_countable($alternatives['larger']) && count($alternatives['larger']) > 0)
                            <h4 class="font-medium text-sm text-amber-800 dark:text-amber-300 mb-2">Available Larger Rooms:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($alternatives['larger'] as $room)
                                    <div class="bg-white dark:bg-slate-800/60 rounded-md shadow-sm p-3 flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-slate-800 dark:text-white">{{ $room->roomType->name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                                <span class="inline-flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    {{ $room->roomType->capacity }} {{ Str::plural('person', $room->roomType->capacity) }}
                                                </span>
                                                <span class="ml-2">${{ number_format($room->roomType->price_per_night * $nights, 0) }} total</span>
                                            </p>
                                        </div>
                                        <a
                                            href="{{ route('bookings.create', [
                                                'room_type_id' => $room->roomType->id,
                                                'check_in_date' => $check_in_date,
                                                'check_out_date' => $check_out_date,
                                                'guests' => $guests
                                            ]) }}"
                                            class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                        >
                                            Book Now
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Book All Larger Rooms Button -->
                            @if (count($alternatives['larger']) > 1)
                                <div class="mt-4 text-right">
                                    <form action="{{ route('bookings.create-multiple') }}" method="GET">
                                        @foreach ($alternatives['larger'] as $room)
                                            <input type="hidden" name="room_ids[]" value="{{ $room->id }}">
                                        @endforeach
                                        <input type="hidden" name="check_in_date" value="{{ $check_in_date }}">
                                        <input type="hidden" name="check_out_date" value="{{ $check_out_date }}">
                                        <input type="hidden" name="guests" value="{{ $guests }}">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                            </svg>
                                            Book All {{ count($alternatives['larger']) }} Rooms Together
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif

                        <!-- For multiple rooms alternative (when a single room can't fit all guests) -->
                        @if (isset($alternatives['multiple']) && is_countable($alternatives['multiple']) && count($alternatives['multiple']) > 0 && $guests > 4)
                            <h4 class="font-medium text-sm text-amber-800 dark:text-amber-300 mt-4 mb-2">Combination of Rooms:</h4>
                            <div class="bg-white dark:bg-slate-800/60 rounded-md shadow-sm p-4">
                                <p class="text-sm text-slate-700 dark:text-slate-300 mb-3">
                                    Consider booking multiple rooms for your group of {{ $guests }} guests. Here are available options:
                                </p>
                                <div class="space-y-2">
                                    @php
                                        $availableCapacity = collect($alternatives['multiple'])->sum(function($room) {
                                            return $room->roomType->capacity;
                                        });
                                        $suggestedRooms = collect($alternatives['multiple'])->sortByDesc(function($room) {
                                            return $room->roomType->capacity;
                                        })->take(3);
                                    @endphp
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        We have a total capacity of {{ $availableCapacity }} guests across {{ count($alternatives['multiple']) }} available rooms.
                                    </p>

                                    <div class="mt-3 flex flex-col gap-2">
                                        @foreach ($suggestedRooms as $room)
                                            <div class="flex justify-between items-center border-b border-slate-200 dark:border-slate-700 pb-2">
                                                <div>
                                                    <span class="text-sm font-medium text-slate-800 dark:text-white">{{ $room->roomType->name }}</span>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                                        <span>Room {{ $room->room_number }}</span> ·
                                                        <span>{{ $room->roomType->capacity }} {{ Str::plural('person', $room->roomType->capacity) }}</span> ·
                                                        <span>${{ number_format($room->roomType->price_per_night * $nights, 0) }}</span>
                                                    </p>
                                                </div>
                                                <a
                                                    href="{{ route('bookings.create', [
                                                        'room_type_id' => $room->roomType->id,
                                                        'check_in_date' => $check_in_date,
                                                        'check_out_date' => $check_out_date,
                                                        'guests' => min($guests, $room->roomType->capacity)
                                                    ]) }}"
                                                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium"
                                                >
                                                    Book this room
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Book All Multiple Rooms Button -->
                                    <div class="mt-6">
                                        <form action="{{ route('bookings.create-multiple') }}" method="GET">
                                            @php
                                                // Select optimal combination of rooms to fit guest count
                                                $remainingGuests = $guests;
                                                $selectedRooms = [];

                                                // First try to fill with larger rooms
                                                foreach ($alternatives['multiple']->sortByDesc(function($room) {
                                                    return $room->roomType->capacity;
                                                }) as $room) {
                                                    if ($remainingGuests > 0) {
                                                        $selectedRooms[] = $room->id;
                                                        $remainingGuests -= $room->roomType->capacity;
                                                    }
                                                }
                                            @endphp

                                            @foreach ($selectedRooms as $roomId)
                                                <input type="hidden" name="room_ids[]" value="{{ $roomId }}">
                                            @endforeach
                                            <input type="hidden" name="check_in_date" value="{{ $check_in_date }}">
                                            <input type="hidden" name="check_out_date" value="{{ $check_out_date }}">
                                            <input type="hidden" name="guests" value="{{ $guests }}">

                                            <div class="flex justify-end">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                                    </svg>
                                                    Book Optimal Room Combination
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Return Link -->
    @if (is_countable($roomTypes) && count($roomTypes) > 0)
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
