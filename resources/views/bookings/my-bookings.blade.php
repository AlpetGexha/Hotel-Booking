<x-layout>
    <x-slot:title>{{ __('My Bookings') }}</x-slot:title>
    <x-slot:metaDescription>{{ __('View your booking history and upcoming reservations at') }} {{ config('app.name') }}</x-slot:metaDescription>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('My Bookings') }}</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('View and manage your stays with us') }}</p>
        </div>

        <!-- Tabs -->
        <div x-data="{ activeTab: 'upcoming' }" class="mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px space-x-8">
                    <button
                        @click="activeTab = 'upcoming'"
                        :class="{'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'upcoming',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'upcoming'}"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('Upcoming Stays') }}
                    </button>
                    <button
                        @click="activeTab = 'past'"
                        :class="{'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'past',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'past'}"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('Past Stays') }}
                    </button>
                </nav>
            </div>

            <!-- Upcoming Bookings Tab -->
            <div x-show="activeTab === 'upcoming'" x-transition class="mt-6">
                <div class="space-y-6">
                    @forelse ($upcomingBookings as $booking)
                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <!-- Booking Header -->
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $booking->roomType->name }} - Room {{ $booking->room->room_number }}
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            {{ __('Booking Reference') }}: <span class="font-medium">{{ $booking->id }}</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ Carbon\Carbon::parse($booking->check_in)->isPast() && Carbon\Carbon::parse($booking->check_out)->isFuture()
                                            ? __('Current Stay')
                                            : __('Upcoming') }}
                                    </div>
                                </div>

                                <!-- Booking Details -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <h4 class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">{{ __('Check-in') }}</h4>
                                        <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ Carbon\Carbon::parse($booking->check_in)->format('D, M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">{{ __('Check-out') }}</h4>
                                        <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ Carbon\Carbon::parse($booking->check_out)->format('D, M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">{{ __('Guests') }}</h4>
                                        <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $booking->guests }} {{ Str::plural('person', $booking->guests) }}</p>
                                    </div>
                                </div>

                                <!-- Guest Information -->
                                <div class="bg-gray-50 dark:bg-slate-700/30 rounded-lg p-4 mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Guest Information') }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('Name') }}:</span>
                                            <span class="ml-1 text-gray-900 dark:text-white">{{ $booking->customer->name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('Email') }}:</span>
                                            <span class="ml-1 text-gray-900 dark:text-white">{{ $booking->customer->email }}</span>
                                        </div>
                                        @if($booking->customer->phone)
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('Phone') }}:</span>
                                            <span class="ml-1 text-gray-900 dark:text-white">{{ $booking->customer->phone }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Price Information -->
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Price') }}</span>
                                        <span class="ml-1 text-lg font-semibold text-gray-900 dark:text-white">${{ number_format($booking->total_price, 2) }}</span>
                                    </div>

                                    @if($booking->special_requests)
                                    <div class="text-right flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ __('Special Requests Added') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">{{ __('No upcoming bookings') }}</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">{{ __('Looking for your next getaway?') }}</p>
                            <div class="mt-6">
                                <a href="{{ route('search.rooms') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Book a Room') }}
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Past Bookings Tab -->
            <div x-show="activeTab === 'past'" x-transition class="mt-6">
                <div class="space-y-6">
                    @forelse ($pastBookings as $booking)
                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <!-- Booking Header -->
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $booking->roomType->name }} - Room {{ $booking->room->room_number }}
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            {{ __('Booking Reference') }}: <span class="font-medium">{{ $booking->id }}</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ __('Completed') }}
                                    </div>
                                </div>

                                <!-- Booking Details -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <h4 class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">{{ __('Check-in') }}</h4>
                                        <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ Carbon\Carbon::parse($booking->check_in)->format('D, M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">{{ __('Check-out') }}</h4>
                                        <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ Carbon\Carbon::parse($booking->check_out)->format('D, M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">{{ __('Guests') }}</h4>
                                        <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $booking->guests }} {{ Str::plural('person', $booking->guests) }}</p>
                                    </div>
                                </div>

                                <!-- Guest Information -->
                                <div class="bg-gray-50 dark:bg-slate-700/30 rounded-lg p-4 mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Guest Information') }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('Name') }}:</span>
                                            <span class="ml-1 text-gray-900 dark:text-white">{{ $booking->customer->name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('Email') }}:</span>
                                            <span class="ml-1 text-gray-900 dark:text-white">{{ $booking->customer->email }}</span>
                                        </div>
                                        @if($booking->customer->phone)
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('Phone') }}:</span>
                                            <span class="ml-1 text-gray-900 dark:text-white">{{ $booking->customer->phone }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Price Information -->
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Price') }}</span>
                                        <span class="ml-1 text-lg font-semibold text-gray-900 dark:text-white">${{ number_format($booking->total_price, 2) }}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('search.rooms') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            {{ __('Book Again') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">{{ __('No past bookings') }}</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">{{ __('Your booking history will appear here') }}</p>
                            <div class="mt-6">
                                <a href="{{ route('search.rooms') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Book a Room') }}
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layout>
