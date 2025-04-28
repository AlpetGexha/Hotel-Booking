<x-layout>
    <x-slot:title>Discover Our Rooms</x-slot:title>
    <x-slot:metaDescription>{{ config('app.name') }} - Explore our luxurious room types for your perfect stay</x-slot:metaDescription>

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-bold text-slate-900 dark:text-white mb-4 relative inline-block">
                    <span class="relative z-10">Our Rooms</span>
                    <span class="absolute -bottom-2 left-0 right-0 h-1 bg-indigo-600 transform scale-x-50 mx-auto w-24"></span>
                </h1>
                <p class="mt-4 max-w-2xl mx-auto text-slate-600 dark:text-slate-400 text-lg">
                    Discover the perfect space tailored to your comfort and style
                </p>
            </div>

            <!-- Rooms Sections with Alternating Layout -->
            <div class="space-y-24 mb-16">
                @forelse ($roomTypes as $index => $roomType)
                    <div class="room-section"
                         x-data="{ hover: false }"
                         @mouseenter="hover = true"
                         @mouseleave="hover = false">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                            <!-- Left side (Details) for even index, Right side for odd index -->
                            <div class="order-2 {{ $index % 2 === 0 ? 'md:order-1' : 'md:order-2' }}">
                                <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-md h-full">
                                    <!-- Title and Price Row -->
                                    <div class="flex justify-between items-center mb-4">
                                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white transition-colors duration-300"
                                           :class="{ 'text-indigo-600 dark:text-indigo-400': hover }">
                                            {{ $roomType->name }}
                                        </h2>
                                        <div class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                            ${{ number_format($roomType->price_per_night, 2) }}
                                            <span class="text-sm font-normal text-slate-500 dark:text-slate-400">/night</span>
                                        </div>
                                    </div>

                                    @if ($roomType->description)
                                        <p class="text-slate-600 dark:text-slate-300 mb-6">
                                            {{ $roomType->description }}
                                        </p>
                                    @endif

                                    <div class="mb-6">
                                        <div class="flex flex-wrap items-center gap-3 mb-4">
                                            @if ($roomType->size)
                                                <span class="flex items-center text-sm bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-3 py-1 rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                                                    </svg>
                                                    {{ $roomType->formattedSize }}
                                                </span>
                                            @endif
                                            <span class="flex items-center text-sm bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-3 py-1 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $roomType->capacity }} {{ Str::plural('person', $roomType->capacity) }}
                                            </span>
                                        </div>

                                        <!-- Amenities -->
                                        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Amenities:</h3>
                                        <div class="grid grid-cols-2 gap-2 mb-8">
                                            @forelse ($roomType->amenities as $amenity)
                                                <span class="flex items-center text-sm text-slate-700 dark:text-slate-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $amenity->name }}
                                                </span>
                                            @empty
                                                <span class="text-sm text-slate-500 dark:text-slate-400">No amenities listed</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- Book Now Button -->
                                    <div class="relative overflow-hidden">
                                        <button class="w-full py-3 px-6 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-all duration-300 relative z-10 group">
                                            <span class="relative z-10">Book Now</span>
                                            <span class="absolute inset-0 bg-indigo-800 transform scale-x-0 origin-left transition-transform duration-300 group-hover:scale-x-100"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Right side (Image) for even index, Left side for odd index -->
                            <div class="order-1 {{ $index % 2 === 0 ? 'md:order-2' : 'md:order-1' }}">
                                <div class="relative overflow-hidden rounded-xl shadow-lg aspect-w-16 aspect-h-10 h-full">
                                    <img
                                        src="{{ $roomType->getThumbnailUrlAttribute() }}"
                                        alt="{{ $roomType->name }}"
                                        class="w-full h-full object-cover transition-transform duration-700"
                                        :class="{ 'scale-110': hover }"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent opacity-0 transition-opacity duration-300"
                                         :class="{ 'opacity-100': hover }"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 flex justify-center items-center">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="mt-4 text-xl font-medium text-slate-700 dark:text-slate-300">No rooms available</h3>
                            <p class="mt-2 text-slate-500 dark:text-slate-400">Check back later for new listings.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('roomSection', () => ({
                    hover: false
                }));
            });
        </script>
    </x-slot:scripts>
</x-layout>
