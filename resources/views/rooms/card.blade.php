<div class="room-card bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-2xl">
    <div class="relative h-64 overflow-hidden">
        <img
            src="{{ $roomType->getThumbnailUrlAttribute() }}"
            alt="{{ $roomType->name }}"
            class="w-full h-full object-cover object-center transform transition-transform duration-700 hover:scale-110"
        >
        <div class="absolute bottom-0 left-0 right-0 px-6 py-3 bg-gradient-to-t from-black/70 to-transparent">
            <span class="inline-block bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                {{ $roomType->formattedSize }}
            </span>
            <span class="ml-2 inline-block bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                {{ $roomType->capacity }} {{ Str::plural('person', $roomType->capacity) }}
            </span>
        </div>
    </div>

    <div class="p-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ $roomType->name }}</h3>

        @if ($roomType->description)
            <p class="text-slate-600 dark:text-slate-300 mb-4 line-clamp-2">{{ $roomType->description }}</p>
        @endif

        <div class="flex flex-wrap gap-2 mb-4">
            @forelse ($roomType->amenities->take(3) as $amenity)
                <span class="inline-flex items-center text-xs bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 px-2 py-1 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    {{ $amenity->name }}
                </span>
            @empty
            @endforelse

            @if ($roomType->amenities->count() > 3)
                <span class="inline-flex items-center text-xs bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 px-2 py-1 rounded">
                    +{{ $roomType->amenities->count() - 3 }} more
                </span>
            @endif
        </div>

        <div class="flex justify-between items-center">
            <div>
                <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                    ${{ number_format($roomType->price_per_night, 2) }}
                </span>
                <span class="text-sm text-slate-500 dark:text-slate-400">/night</span>
            </div>
            <button class="relative overflow-hidden px-6 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-all duration-300 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none group">
                <span class="relative z-10">Book Now</span>
                <span class="absolute inset-0 bg-indigo-700 transform scale-x-0 origin-left transition-transform duration-300 group-hover:scale-x-100"></span>
            </button>
        </div>
    </div>
</div>
