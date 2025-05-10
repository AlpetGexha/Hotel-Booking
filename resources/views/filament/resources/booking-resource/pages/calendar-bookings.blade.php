<x-filament-panels::page>
    <!-- Include custom calendar styles -->
    <link rel="stylesheet" href="{{ asset('css/calendar-styles.css') }}">

    <div class="filament-widgets-container">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-1 mb-6 calendar-widget-container">
            @foreach ($this->getHeaderWidgets() as $widget)
                {{ $widget }}
            @endforeach
        </div>
    </div>

    <div class="flex justify-center mt-4 mb-6">
        <x-filament::link
            href="{{ route('filament.admin.resources.bookings.index') }}"
            color="gray"
            icon="heroicon-o-table-cells"
            class="px-4 py-2"
        >
            Return to List View
        </x-filament::link>
    </div>

    <!-- Calendar instructions -->
    <div class="text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mt-4">
        <p class="mb-2"><strong>Calendar Instructions:</strong></p>
        <ul class="list-disc pl-5 space-y-1">
            <li>Click on a booking to view complete details</li>
            <li>Use the buttons in the top-right to switch between different views (month, week, day)</li>
            <li>Navigate between time periods using the arrows in the top-left</li>
        </ul>
    </div>
</x-filament-panels::page>
