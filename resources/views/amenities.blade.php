<x-layout>
    <x-slot:title>Amenities & Offerings</x-slot:title>
    <x-slot:metaDescription>{{ config('app.name') }} - Discover our premium hotel amenities and offerings</x-slot:metaDescription>

    <!-- Main Content -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">AMENITIES & OFFERINGS</h1>
                <h2 class="text-2xl md:text-3xl font-semibold text-slate-800 dark:text-slate-200 mb-6">What we offer you!</h2>
                <p class="max-w-2xl mx-auto text-slate-600 dark:text-slate-400">
                    To make sure you enjoy your stay in our hotel here are all Amenities & services possibilities of our property
                </p>
                <div class="w-24 h-1 bg-indigo-600 mx-auto mt-8"></div>
            </div>

            <!-- Amenities Grid First Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- Transportation -->
                <div class="amenity-card bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:-translate-y-2 hover:shadow-lg">
                    <div class="h-48 bg-slate-200 dark:bg-slate-700 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=800&auto=format&fit=crop&q=60" alt="Transportation" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 5h8m-8 5h8" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white">Transportation</h3>
                    </div>
                </div>

                <!-- Free Wi-Fi -->
                <div class="amenity-card bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:-translate-y-2 hover:shadow-lg">
                    <div class="h-48 bg-slate-200 dark:bg-slate-700 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800&auto=format&fit=crop&q=60" alt="Free Wi-Fi" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a12.5 12.5 0 014.777 0m-4.777 0a9.5 9.5 0 014.777 0m-4.777 0a6.5 6.5 0 014.777 0m-4.777 0a3.5 3.5 0 014.777 0M12 20a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white">Free Wi-Fi</h3>
                    </div>
                </div>

                <!-- Free Parking -->
                <div class="amenity-card bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:-translate-y-2 hover:shadow-lg">
                    <div class="h-48 bg-slate-200 dark:bg-slate-700 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1590674899484-d5640e854abe?w=800&auto=format&fit=crop&q=60" alt="Free Parking" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white">Free Parking</h3>
                    </div>
                </div>

                <!-- HD TV -->
                <div class="amenity-card bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:-translate-y-2 hover:shadow-lg">
                    <div class="h-48 bg-slate-200 dark:bg-slate-700 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1593784991095-a205069470b6?w=800&auto=format&fit=crop&q=60" alt="HD TV" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white">HD TV</h3>
                    </div>
                </div>
            </div>

            <!-- Amenities Grid Second Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Comfortable Beds -->
                <div class="amenity-card bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:-translate-y-2 hover:shadow-lg">
                    <div class="h-48 bg-slate-200 dark:bg-slate-700 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800&auto=format&fit=crop&q=60" alt="Comfortable Beds" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white">Comfortable Beds</h3>
                    </div>
                </div>

                <!-- Room Service -->
                <div class="amenity-card bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:-translate-y-2 hover:shadow-lg">
                    <div class="h-48 bg-slate-200 dark:bg-slate-700 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1628610654262-93b1a35cb731?w=800&auto=format&fit=crop&q=60" alt="Room Service" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white">Room Service</h3>
                    </div>
                </div>

                <!-- Mini Bar -->
                <div class="amenity-card bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:-translate-y-2 hover:shadow-lg">
                    <div class="h-48 bg-slate-200 dark:bg-slate-700 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1582056509381-33c1ce732b8d?w=800&auto=format&fit=crop&q=60" alt="Mini Bar" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white">Mini Bar</h3>
                    </div>
                </div>

                <!-- Air Conditioning -->
                <div class="amenity-card bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:-translate-y-2 hover:shadow-lg">
                    <div class="h-48 bg-slate-200 dark:bg-slate-700 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1631049035182-249067d7618e?w=800&auto=format&fit=crop&q=60" alt="Air Conditioning" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white">Air Conditioning</h3>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-16 bg-white dark:bg-slate-800 rounded-lg shadow-md p-8">
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Additional Services</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">24/7 Reception</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Our front desk is always ready to assist you</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Laundry Service</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Same-day laundry and dry cleaning</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Spa & Wellness</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Relax and rejuvenate with our wellness services</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Airport Shuttle</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Complimentary airport pickup and drop-off</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Business Center</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Fully equipped for all your business needs</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Concierge Services</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Let us help you plan your perfect stay</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('amenities', () => ({
                    init() {
                        // Initialize any JavaScript functionality for the amenities page here
                    }
                }));
            });
        </script>
    </x-slot:scripts>
</x-layout>
