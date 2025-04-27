<x-layout>
    <x-slot:title>Welcome</x-slot:title>
    <x-slot:metaDescription>Luxury Hotel Booking - Find your perfect stay</x-slot:metaDescription>

    <!-- Hero Section with Background Image -->
    <div class="relative bg-cover bg-center h-[70vh] -mt-24 mb-24" style="background-image: url('{{ asset('images/hotel-bg.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl md:text-6xl">
                    <span class="block">Experience Luxury</span>
                    <span class="block">Like Never Before</span>
                </h1>
                <p class="mt-4 text-xl text-white opacity-90">
                    Our premium rooms and exceptional service will make your stay unforgettable.
                </p>
            </div>
        </div>
    </div>

    <!-- Search Form Section -->
    <div class="relative z-10 max-w-5xl mx-auto px-4 py-5 sm:px-6 -mt-48">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl overflow-hidden">
            <div class="px-6 py-8">
                <h2 class="text-2xl font-semibold text-slate-800 dark:text-white mb-6">Find Your Perfect Room</h2>
                <form method="GET" action="{{ route('search.rooms') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Check-in Date -->
                    <div>
                        <label for="check_in_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Check-in Date</label>
                        <input
                            type="date"
                            id="check_in_date"
                            name="check_in_date"
                            value="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                            class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- Check-out Date -->
                    <div>
                        <label for="check_out_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Check-out Date</label>
                        <input
                            type="date"
                            id="check_out_date"
                            name="check_out_date"
                            value="{{ \Carbon\Carbon::tomorrow()->addDays(5)->format('Y-m-d') }}"
                            min="{{ \Carbon\Carbon::tomorrow()->addDay()->format('Y-m-d') }}"
                            class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- Guests -->
                    <div>
                        <label for="guests" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Guests</label>
                        <select id="guests" name="guests" class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach (range(1, 6) as $i)
                                <option value="{{ $i }}">{{ $i }} {{ Str::plural('Person', $i) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Search Availability
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Featured Rooms Section -->
    <div class="py-12 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Featured Rooms</h2>
                <p class="mt-4 max-w-2xl text-lg text-slate-500 dark:text-slate-400 mx-auto">
                    Choose from our selection of luxury rooms and suites
                </p>
            </div>

            <div class="mt-10 grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <!-- Room Card 1 -->
                <div class="overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl bg-white dark:bg-slate-800">
                    <div class="relative pb-2/3">
                        <img
                            src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Deluxe Room"
                            class="h-64 w-full object-cover"
                        >
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Deluxe Room</h3>
                        <p class="mt-2 text-slate-500 dark:text-slate-400">Perfect for solo travelers or couples looking for comfort and style.</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-indigo-600 dark:text-indigo-500 font-bold">$199 / night</span>
                            <a href="#" class="text-sm font-medium text-indigo-600 dark:text-indigo-500 hover:text-indigo-500 dark:hover:text-indigo-400">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Room Card 2 -->
                <div class="overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl bg-white dark:bg-slate-800">
                    <div class="relative pb-2/3">
                        <img
                            src="https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Premium Suite"
                            class="h-64 w-full object-cover"
                        >
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Premium Suite</h3>
                        <p class="mt-2 text-slate-500 dark:text-slate-400">Spacious suite with separate living area and premium amenities.</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-indigo-600 dark:text-indigo-500 font-bold">$349 / night</span>
                            <a href="#" class="text-sm font-medium text-indigo-600 dark:text-indigo-500 hover:text-indigo-500 dark:hover:text-indigo-400">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Room Card 3 -->
                <div class="overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl bg-white dark:bg-slate-800">
                    <div class="relative pb-2/3">
                        <img
                            src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Family Room"
                            class="h-64 w-full object-cover"
                        >
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Family Room</h3>
                        <p class="mt-2 text-slate-500 dark:text-slate-400">Comfortable accommodations for the whole family with extra space.</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-indigo-600 dark:text-indigo-500 font-bold">$299 / night</span>
                            <a href="#" class="text-sm font-medium text-indigo-600 dark:text-indigo-500 hover:text-indigo-500 dark:hover:text-indigo-400">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:styles>
        <style>
            /* Custom styles for the homepage */
            main {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }

            main > div {
                max-width: 100% !important;
                padding: 0 !important;
            }
        </style>
    </x-slot:styles>
</x-layout>
