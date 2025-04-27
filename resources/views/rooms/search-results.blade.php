<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Luxury Hotel Booking - Available Rooms">

        <title>{{ config('app.name', 'Luxury Hotel') }} - Available Rooms</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:100,200,300,400,500,600,700,800,900" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-slate-50 dark:bg-slate-900">
        <div class="min-h-screen flex flex-col">
            <!-- Header/Navigation -->
            <nav class="bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600 dark:text-indigo-500">
                                    Luxury Hotel
                                </a>
                            </div>
                        </div>
                        <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-8">
                            <a href="#" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Rooms</a>
                            <a href="#" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Amenities</a>
                            <a href="#" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Gallery</a>
                            <a href="#" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Contact</a>

                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Log in</a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition ease-in-out duration-150">Register</a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main content area -->
            <main class="flex-grow py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Search Results Header Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md mb-6">
                        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Available Room Types</h1>

                            <div class="mt-2 flex flex-wrap items-center gap-4 text-slate-600 dark:text-slate-400">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ Carbon\Carbon::parse($check_in_date)->format('M d') }} - {{ Carbon\Carbon::parse($check_out_date)->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ $guests }} {{ Str::plural('guest', $guests) }} for {{ $nights }} {{ Str::plural('night', $nights) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Search Form -->
                        <div x-data="{ showFilters: false }" class="p-6 bg-slate-50 dark:bg-slate-900/50">
                            <button
                                @click="showFilters = !showFilters"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-md font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-800"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Modify Search
                            </button>

                            <!-- Expanded Form -->
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
                                <form method="GET" action="{{ route('search.rooms') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <!-- Check-in Date -->
                                    <div>
                                        <label for="check_in_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Check-in Date</label>
                                        <input
                                            type="date"
                                            id="check_in_date"
                                            name="check_in_date"
                                            value="{{ $check_in_date }}"
                                            min="{{ now()->toDateString() }}"
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
                                            value="{{ $check_out_date }}"
                                            min="{{ Carbon\Carbon::parse($check_in_date)->addDay()->toDateString() }}"
                                            class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>

                                    <!-- Guests -->
                                    <div>
                                        <label for="guests" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Guests</label>
                                        <select id="guests" name="guests" class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach (range(1, 10) as $i)
                                                <option value="{{ $i }}" {{ $guests == $i ? 'selected' : '' }}>
                                                    {{ $i }} {{ Str::plural('Person', $i) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex items-end">
                                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                            <x-room-type-card
                                :room-type="$roomType"
                                :check-in-date="$check_in_date"
                                :check-out-date="$check_out_date"
                                :guests="$guests"
                                :nights="$nights"
                            />
                        @empty
                            <div class="col-span-3 bg-white dark:bg-slate-800 rounded-lg shadow-md p-8 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-white">No rooms available</h3>
                                <p class="mt-2 text-slate-600 dark:text-slate-400">
                                    We couldn't find any rooms matching your criteria. Please try different dates, guest count, or filters.
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('home') }}" class="text-indigo-600 dark:text-indigo-500 hover:text-indigo-500 dark:hover:text-indigo-400">
                                        &larr; Back to search
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Return Link -->
                    @if ($roomTypes->count() > 0)
                        <div class="mt-8 text-center">
                            <a href="{{ route('home') }}" class="text-indigo-600 dark:text-indigo-500 hover:text-indigo-500 dark:hover:text-indigo-400 inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Return to Home
                            </a>
                        </div>
                    @endif
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-slate-800 dark:bg-slate-950 text-white py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <!-- Column 1: About -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Luxury Hotel</h3>
                            <p class="text-slate-300 text-sm">
                                Experience the pinnacle of luxury and comfort at our premium hotel. Our dedicated staff is committed to making your stay unforgettable.
                            </p>
                        </div>

                        <!-- Column 2: Contact -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                            <ul class="space-y-2 text-slate-300 text-sm">
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>123 Luxury Avenue, Prestige City</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>contact@luxuryhotel.com</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>+1 (555) 123-4567</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Column 3: Quick Links -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                            <ul class="space-y-2 text-slate-300 text-sm">
                                <li><a href="#" class="hover:text-indigo-400 transition duration-150">Our Rooms</a></li>
                                <li><a href="#" class="hover:text-indigo-400 transition duration-150">Amenities</a></li>
                                <li><a href="#" class="hover:text-indigo-400 transition duration-150">Gallery</a></li>
                                <li><a href="#" class="hover:text-indigo-400 transition duration-150">Events</a></li>
                                <li><a href="#" class="hover:text-indigo-400 transition duration-150">Special Offers</a></li>
                            </ul>
                        </div>

                        <!-- Column 4: Social Media -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="text-slate-300 hover:text-indigo-400 transition duration-150">
                                    <span class="sr-only">Facebook</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="#" class="text-slate-300 hover:text-indigo-400 transition duration-150">
                                    <span class="sr-only">Instagram</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.379-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="#" class="text-slate-300 hover:text-indigo-400 transition duration-150">
                                    <span class="sr-only">Twitter</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-slate-700 text-sm text-slate-400 text-center">
                        <p>&copy; {{ date('Y') }} Luxury Hotel. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
