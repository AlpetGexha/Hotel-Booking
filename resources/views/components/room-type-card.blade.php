@props([
    'roomType',
    'checkInDate',
    'checkOutDate',
    'guests',
    'nights',
])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-xl">
    <!-- Room Image with interactive hover effect -->
    <div class="relative h-56 overflow-hidden group">
        <img
            src="{{ $roomType->image_url ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
            alt="{{ $roomType->name }}"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
            loading="lazy"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        <!-- Room Type Badge -->
        <div class="absolute top-4 left-4">
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                {{ $roomType->name }}
            </span>
        </div>

        <!-- Capacity Badge -->
        <div class="absolute top-4 right-4">
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ $roomType->capacity }}
            </span>
        </div>
    </div>

    <!-- Room Details -->
    <div class="p-5 flex-grow flex flex-col">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $roomType->name }}</h2>
            <div class="text-right">
                <p class="text-indigo-600 dark:text-indigo-400 font-bold">${{ number_format($roomType->price_per_night, 2) }}</p>
                <span class="text-xs text-gray-500 dark:text-gray-400">per night</span>
            </div>
        </div>

        <!-- Room Description -->
        <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
            {{ $roomType->description ?? 'Experience luxury and comfort in our ' . Str::lower($roomType->name) . '. Perfect for your stay with all the amenities you need.' }}
        </p>

        <!-- Room Details (Size, etc.) -->
        <div class="flex flex-wrap items-center gap-3 mb-4">
            @if($roomType->size)
            <div class="flex items-center text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                </svg>
                <span class="text-sm font-medium">{{ $roomType->formatted_size }}</span>
            </div>
            @endif

            <div class="flex items-center text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-sm font-medium">{{ $roomType->capacity }} {{ Str::plural('Person', $roomType->capacity) }}</span>
            </div>
        </div>

        <!-- Amenities -->
        @if($roomType->amenities->count() > 0)
            <div class="mb-4 mt-auto">
                <h3 class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400 mb-2">Amenities</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($roomType->amenities as $amenity)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if(Str::contains(Str::lower($amenity->name), ['wifi', 'internet']))
                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif(Str::contains(Str::lower($amenity->name), ['tv', 'television', 'entertainment']))
                                bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                            @elseif(Str::contains(Str::lower($amenity->name), ['air', 'conditioning', 'climate']))
                                bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200
                            @elseif(Str::contains(Str::lower($amenity->name), ['bar', 'drink', 'minibar']))
                                bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200
                            @elseif(Str::contains(Str::lower($amenity->name), ['safe', 'security']))
                                bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200
                            @elseif(Str::contains(Str::lower($amenity->name), ['kitchen', 'cooking']))
                                bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200
                            @elseif(Str::contains(Str::lower($amenity->name), ['living', 'room', 'space']))
                                bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-200
                            @else
                                bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                            @endif">
                            {{ $amenity->name }}
                        </span>
                    @endforeach

                    {{-- @if($roomType->amenities->count() > 5)
                        <span
                            x-data="{ showAllAmenities: false }"
                            class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 cursor-pointer"
                            @click="showAllAmenities = !showAllAmenities"
                            x-on:click.away="showAllAmenities = false"
                        >
                            <span x-show="!showAllAmenities">+{{ $roomType->amenities->count() - 5 }}</span>
                            <span x-show="showAllAmenities">Close</span>

                            <!-- Dropdown with all amenities -->
                            <div
                                x-show="showAllAmenities"
                                x-transition
                                class="absolute z-10 mt-2 p-3 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700"
                                style="min-width: 200px"
                            >
                                <div class="grid grid-cols-1 gap-1">
                                    @foreach($roomType->amenities as $amenity)
                                        <span class="px-2.5 py-0.5 text-xs font-medium">
                                            {{ $amenity->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </span>
                    @endif --}}
                </div>
            </div>
        @endif

        <!-- Price calculation and Book Now -->
        <div class="pt-4 mt-auto border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Total for {{ $nights }} {{ Str::plural('night', $nights) }}</span>
                    <p class="font-bold text-gray-900 dark:text-white">${{ number_format($roomType->price_per_night * $nights, 2) }}</p>
                </div>

                <a
                    {{-- href="{{ route('booking.create', [
                        'room_type_id' => $roomType->id,
                        'check_in_date' => $checkInDate,
                        'check_out_date' => $checkOutDate,
                        'guests' => $guests
                    ]) }}" --}}
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                >
                    Book Now
                </a>
            </div>
        </div>
    </div>
</div>
