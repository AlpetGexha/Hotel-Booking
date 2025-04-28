<x-layout>
    <x-slot:title>Complete Your Booking</x-slot:title>
    <x-slot:metaDescription>Book your stay at {{ config('app.name') }} - Complete your reservation for
        {{ $roomType->name }}</x-slot:metaDescription>

    <!-- Hero Section with Background Image --> {{-- <div class="relative bg-cover bg-center h-[50vh] -mt-24 mb-16"
         style="background-image: url('{{ asset('images/hotel-bg.jpg') }}'); background-repeat: no-repeat; background-size: cover;">
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>          <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl md:text-5xl">
                    <span class="block">Complete Your Booking</span>
                </h1>
                <p class="mt-4 text-xl text-white opacity-90">                      You're just a few steps away from your luxurious stay
                </p>
            </div>
        </div>
    </div> --}}

    <!-- Booking Form Section -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 mt-12">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden">

            {{-- get all the errors --}}
            @if ($errors->any())
                <div
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 p-4 rounded-md mb-6">
                    <h3 class="font-medium">Please fix the following errors:</h3>
                    <ul class="list-disc pl-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Room Type Summary -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 py-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $roomType->name }}</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $nights }} {{ Str::plural('night', $nights) }}, {{ $guests }}
                            {{ Str::plural('guest', $guests) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Price</p>
                        <p class="text-xl font-bold text-indigo-600 dark:text-indigo-500">
                            ${{ number_format($totalPrice, decimals: 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Dates Summary -->
            <div class="bg-gray-50 dark:bg-slate-900/50 px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Check-in</p>
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($checkInDate)->format('D, M d, Y') }} </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Check-out</p>
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($checkOutDate)->format('D, M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- Booking Form -->
            <div class="px-6 py-8">
                <form method="POST" action="{{ route('bookings.store') }}" x-data="{ bookingFor: '{{ old('booking_for', 'self') }}' }">
                    @csrf

                    <!-- Hidden Fields -->
                    <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                    <input type="hidden" name="check_in_date" value="{{ $checkInDate }}">
                    <input type="hidden" name="check_out_date" value="{{ $checkOutDate }}">
                    <input type="hidden" name="guests" value="{{ $guests }}">
                    <input type="hidden" name="total_price" value="{{ $totalPrice }}">

                    <!-- Booking Options Section -->
                    @auth
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Booking Options</h3>
                            <div class="flex flex-col space-y-3">
                                <div class="flex items-center">
                                    <input type="radio" id="booking_self" name="booking_for" value="self"
                                        x-model="bookingFor" {{ old('booking_for', 'self') === 'self' ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600">
                                    <label for="booking_self" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        Book for myself
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="booking_other" name="booking_for" value="other"
                                        x-model="bookingFor" {{ old('booking_for') === 'other' ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600">
                                    <label for="booking_other" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                        Book for someone else
                                    </label>
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="booking_for" value="other">
                    @endauth

                    <!-- Guest Information Section -->
                    <div id="guest-info-section" x-show="bookingFor === 'other'" x-transition>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Guest Information</h3>

                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full
                                Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                x-bind:required="bookingFor === 'other'"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email
                                Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                x-bind:required="bookingFor === 'other'"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone (new field) -->
                        <div class="mb-6">
                            <label for="phone"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number
                                (Optional)</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- User Information Display (when booking for self) -->
                    @auth
                        <div class="mb-6" x-show="bookingFor === 'self'" x-transition>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Your Information</h3>

                            <div class="bg-gray-50 dark:bg-slate-700/30 rounded-lg p-4">
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</span>
                                        <span class="text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</span>
                                        <span class="text-gray-900 dark:text-white">{{ auth()->user()->email }}</span>
                                    </div>

                                    @if (auth()->user()->phone)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</span>
                                            <span class="text-gray-900 dark:text-white">{{ auth()->user()->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endauth

                    <div class="mb-8">
                        <label for="special_requests"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Special Requests
                            (Optional)</label>
                        <textarea id="special_requests" name="special_requests" rows="3"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('special_requests') }}</textarea>
                    </div>

                    <!-- Payment Method Selection -->
                    @if (false)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Payment Method</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3" x-data="{ selected: '{{ old('payment_method', \App\Enum\PaymentMethod::CASH->value) }}' }">
                                @foreach (\App\Enum\PaymentMethod::cases() as $method)
                                    <div class="relative rounded-lg border-2 transition-all duration-200 ease-in-out cursor-pointer shadow-sm hover:shadow"
                                        :class="selected === '{{ $method->value }}' ?
                                            'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' :
                                            'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-600/50'"
                                        @click="selected = '{{ $method->value }}'">
                                        <input id="payment_method_{{ $method->value }}" name="payment_method"
                                            type="radio" value="{{ $method->value }}" x-model="selected"
                                            class="sr-only">
                                        <div class="p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <i
                                                        class="{{ $method->getIcon() }} w-6 h-6 text-{{ $method->getColor() }}-500"></i>
                                                    <span
                                                        class="ml-3 font-medium text-gray-800 dark:text-gray-200">{{ $method->label() }}</span>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                                        :class="selected === '{{ $method->value }}' ?
                                                            'border-indigo-600 bg-indigo-600' :
                                                            'border-gray-300 dark:border-gray-600'">
                                                        <div class="w-2 h-2 rounded-full bg-white"
                                                            x-show="selected === '{{ $method->value }}'"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                @switch($method->value)
                                                    @case(\App\Enum\PaymentMethod::CASH->value)
                                                        Pay with cash upon arrival
                                                    @break

                                                    @case(\App\Enum\PaymentMethod::CREDIT_CARD->value)
                                                        All major credit cards accepted
                                                    @break

                                                    @default
                                                        Select payment method
                                                @endswitch
                                            </p>
                                        </div>

                                        <div class="absolute inset-0 rounded-lg pointer-events-none"
                                            aria-hidden="true"
                                            :class="selected === '{{ $method->value }}' ? 'border-2 border-indigo-600' :
                                                'border border-transparent'">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('payment_method')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                    <!-- Payment Notice -->
                    <div class="mb-8 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-400">Payment
                                    Information</h3>
                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                    <p>Payment will be collected upon arrival. We accept all major credit cards, debit
                                        cards, and cash.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Confirm Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot:styles>
        <style>
            /* Custom styles for the booking page */
            main {
                padding-top: 0 !important;
            }

            main>div {
                max-width: 100% !important;
                padding: 0 !important;
            }

            /* Enhanced background image handling */
            .relative.bg-cover.bg-center {
                background-color: #1e293b;
                /* A dark slate color as fallback */
                position: relative;
                isolation: isolate;
            }

            /* Create a pseudo-element with the same background to prevent rendering issues */
            .relative.bg-cover.bg-center::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image: inherit;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                z-index: -1;
                opacity: 1;
            }
        </style>
    </x-slot:styles>
</x-layout>
