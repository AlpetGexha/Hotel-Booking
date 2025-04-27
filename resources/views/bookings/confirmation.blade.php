<x-layout>
    <x-slot:title>Booking Confirmation</x-slot:title>
    <x-slot:metaDescription>Your booking has been confirmed successfully</x-slot:metaDescription>

    <!-- Hero Section with Background Image -->
    <div class="relative bg-cover bg-center h-[70vh] -mt-24 mb-24" style="background-image: url('{{ asset('images/hotel-bg.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl md:text-6xl">
                    <span class="block">Booking Confirmed</span>
                </h1>
                <p class="mt-4 text-xl text-white opacity-90">
                    Thank you for choosing our hotel for your stay
                </p>
            </div>
        </div>
    </div>

    <!-- Confirmation Message Section -->
    <div class="relative z-10 max-w-5xl mx-auto px-4 py-5 sm:px-6 -mt-48 mb-16">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl overflow-hidden">
            <div class="px-6 py-8">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <h2 class="text-2xl font-semibold text-slate-800 dark:text-white mb-4">Booking Successful!</h2>

                    @if (request()->has('multiple') && request('multiple'))
                        <p class="text-slate-600 dark:text-slate-300 mb-8">
                            Your {{ request('booking_count', 2) }} room bookings have been confirmed and are now reserved.
                            A confirmation email has been sent to your email address with all the details.
                        </p>
                    @else
                        <p class="text-slate-600 dark:text-slate-300 mb-8">
                            Your booking has been confirmed and is now reserved. A confirmation email has been sent to your email address with all the details.
                        </p>
                    @endif

                    <div class="bg-slate-50 dark:bg-slate-700 p-4 rounded-lg mb-8 inline-block">
                        <p class="text-slate-700 dark:text-slate-300">
                            <span class="font-medium">Booking Reference:</span> #{{ $booking->id }}
                            @if (request()->has('multiple') && request('multiple'))
                                <span class="text-sm text-slate-500 dark:text-slate-400">
                                    (Primary booking, {{ request('booking_count', 2) - 1 }} additional bookings created)
                                </span>
                            @endif
                        </p>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('home') }}" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Return to Homepage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:styles>
        <style>
            /* Custom styles for the confirmation page */
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
