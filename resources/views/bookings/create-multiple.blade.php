<x-layout>
    <x-slot:title>Book Multiple Rooms</x-slot:title>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Booking Header -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md mb-6 overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Book Multiple Rooms</h1>
                <p class="mt-2 text-slate-600 dark:text-slate-400">
                    Complete your booking for {{ count($rooms) }} rooms
                </p>
            </div>

            <!-- Booking Summary -->
            <div class="p-6 bg-slate-50 dark:bg-slate-900/50">
                <div class="flex flex-wrap items-center gap-6 text-slate-600 dark:text-slate-400">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ Carbon\Carbon::parse($checkInDate)->format('M d') }} -
                            {{ Carbon\Carbon::parse($checkOutDate)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ $guests }} {{ Str::plural('guest', $guests) }} · {{ $nights }}
                            {{ Str::plural('night', $nights) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Room Details (Left Side) -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Selected Rooms</h2>

                    <!-- Selected Rooms -->
                    <div class="space-y-6">
                        @forelse ($rooms as $room)
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-6 last:border-b-0 last:pb-0">
                                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">
                                    {{ $room->roomType->name }} - Room {{ $room->room_number }}
                                </h3>
                                <div class="flex flex-wrap gap-4 mb-3">
                                    <!-- Capacity -->
                                    <div class="flex items-center text-slate-600 dark:text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-indigo-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ $room->roomType->capacity }}
                                            {{ Str::plural('person', $room->roomType->capacity) }}</span>
                                    </div>

                                    <!-- Room Size -->
                                    @if ($room->roomType->size)
                                        <div class="flex items-center text-slate-600 dark:text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-indigo-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                                            </svg>
                                            <span>{{ $room->roomType->size }} m²</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Pricing -->
                                <div class="bg-slate-50 dark:bg-slate-700/30 rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-slate-700 dark:text-slate-300">Price per night</span>
                                        <span
                                            class="font-medium">${{ number_format($roomPrices[$room->id]['price_per_night'], 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-slate-700 dark:text-slate-300">{{ $nights }}
                                            {{ Str::plural('night', $nights) }}</span>
                                        <span
                                            class="font-medium">${{ number_format($roomPrices[$room->id]['price_per_night'] * $nights, 2) }}</span>
                                    </div>
                                    <div
                                        class="border-t border-slate-200 dark:border-slate-600 my-2 pt-2 flex justify-between items-center">
                                        <span class="font-medium text-slate-900 dark:text-white">Total for this
                                            room</span>
                                        <span class="font-bold text-indigo-600 dark:text-indigo-400">
                                            ${{ number_format($roomPrices[$room->id]['total_price'], 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-slate-500 dark:text-slate-400">
                                No rooms selected. <a href="{{ route('search.rooms') }}"
                                    class="text-indigo-600 hover:text-indigo-500">Search for rooms</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Booking Form (Right Side) -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 sticky top-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Your Information</h2>

                    {{-- show all errors --}}
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="text-red-600 dark:text-red-500">
                                <strong>Please fix the following errors:</strong>
                            </div>
                            <ul class="list-disc pl-5 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('bookings.store-multiple') }}" x-data="{ bookingFor: '{{ old('booking_for', 'self') }}' }">
                        @csrf
                        <!-- Hidden fields -->
                        @foreach ($rooms as $room)
                            <input type="hidden" name="room_ids[]" value="{{ $room->id }}">
                        @endforeach
                        <input type="hidden" name="check_in_date" value="{{ $checkInDate }}">
                        <input type="hidden" name="check_out_date" value="{{ $checkOutDate }}">
                        <input type="hidden" name="guests" value="{{ $guests }}">

                        <!-- Booking Options Section -->
                        @auth
                        <div class="mb-6">
                            <h3 class="text-base font-medium text-slate-900 dark:text-white mb-4">Booking Options</h3>
                            <div class="flex flex-col space-y-3">
                                <div class="flex items-center">
                                    <input
                                        type="radio"
                                        id="booking_self"
                                        name="booking_for"
                                        value="self"
                                        x-model="bookingFor"
                                        {{ old('booking_for', 'self') === 'self' ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600"
                                    >
                                    <label for="booking_self" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">
                                        Book for myself
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input
                                        type="radio"
                                        id="booking_other"
                                        name="booking_for"
                                        value="other"
                                        x-model="bookingFor"
                                        {{ old('booking_for') === 'other' ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600"
                                    >
                                    <label for="booking_other" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">
                                        Book for someone else
                                    </label>
                                </div>
                            </div>
                        </div>
                        @else
                            <input type="hidden" name="booking_for" value="other">
                        @endauth

                        <!-- Guest Information -->
                        <div class="space-y-4 mb-6" x-show="bookingFor === 'other'" x-transition>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">First
                                        Name</label>
                                    <input type="text" id="first_name" name="first_name"
                                        value="{{ old('first_name') }}"
                                        x-bind:required="bookingFor === 'other'"
                                        class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="last_name"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Last
                                        Name</label>
                                    <input type="text" id="last_name" name="last_name"
                                        value="{{ old('last_name') }}"
                                        x-bind:required="bookingFor === 'other'"
                                        class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    x-bind:required="bookingFor === 'other'"
                                    class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Phone (Optional)</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- User Information Display (when booking for self) -->
                        @auth
                        <div class="mb-6" x-show="bookingFor === 'self'" x-transition>
                            <h3 class="text-base font-medium text-slate-900 dark:text-white mb-4">Your Information</h3>

                            <div class="bg-slate-50 dark:bg-slate-700/30 rounded-lg p-4">
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Name</span>
                                        <span class="text-slate-900 dark:text-white">{{ auth()->user()->name }}</span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Email</span>
                                        <span class="text-slate-900 dark:text-white">{{ auth()->user()->email }}</span>
                                    </div>

                                    @if(auth()->user()->phone)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Phone</span>
                                        <span class="text-slate-900 dark:text-white">{{ auth()->user()->phone }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endauth

                        <div class="mb-6">
                            <label for="special_requests"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Special
                                Requests (Optional)</label>
                            <textarea id="special_requests" name="special_requests" rows="3"
                                class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('special_requests') }}</textarea>
                            @error('special_requests')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="mb-6">
                            <h3 class="text-base font-medium text-slate-900 dark:text-white mb-4">Payment Method</h3>
                            <div class="grid grid-cols-1 gap-3">
                                @foreach (\App\Enum\PaymentMethod::cases() as $method)
                                <div class="relative flex items-start">
                                    <div class="flex h-6 items-center">
                                        <input
                                            id="payment_method_{{ $method->value }}"
                                            name="payment_method"
                                            type="radio"
                                            value="{{ $method->value }}"
                                            {{ old('payment_method', \App\Enum\PaymentMethod::CASH->value) === $method->value ? 'checked' : '' }}
                                            class="h-4 w-4 rounded-full border-gray-300 text-indigo-600 focus:ring-indigo-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800"
                                        >
                                    </div>
                                    <div class="ml-3 text-sm leading-6">
                                        <label for="payment_method_{{ $method->value }}" class="flex items-center font-medium text-gray-700 dark:text-gray-300">
                                            <i class="{{ $method->icon() }} w-5 h-5 mr-2 text-{{ $method->color() }}-500"></i>
                                            {{ $method->label() }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price Summary -->
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-lg p-4 mb-6">
                            <h3 class="font-medium text-slate-900 dark:text-white mb-4">Price Summary</h3>

                            @foreach ($rooms as $room)
                                <div class="flex justify-between items-center mb-2 text-sm">
                                    <span class="text-slate-700 dark:text-slate-300">
                                        {{ $room->roomType->name }} (Room {{ $room->room_number }})
                                    </span>
                                    <span>${{ number_format($roomPrices[$room->id]['total_price'], 2) }}</span>
                                </div>
                            @endforeach

                            <div class="border-t border-slate-200 dark:border-slate-600 my-2 pt-2"></div>

                            <div class="flex justify-between items-center font-bold">
                                <span class="text-slate-900 dark:text-white">Total</span>
                                <span
                                    class="text-lg text-indigo-600 dark:text-indigo-400">${{ number_format($totalPrice, 2) }}</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-medium">
                            Confirm Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
