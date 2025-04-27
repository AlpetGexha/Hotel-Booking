<?php

namespace App\Livewire;

use App\Models\RoomType;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class RoomTypeCard extends Component
{
    public RoomType $roomType;
    public string $checkInDate;
    public string $checkOutDate;
    public int $guests;
    public int $nights;
    public bool $isLoading = true;

    public function mount(RoomType $roomType, string $checkInDate, string $checkOutDate, int $guests, int $nights): void
    {
        $this->roomType = $roomType;
        $this->checkInDate = $checkInDate;
        $this->checkOutDate = $checkOutDate;
        $this->guests = $guests;
        $this->nights = $nights;
    }

    public function placeholder(): string
    {
        return <<<'HTML'
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden h-full animate-pulse">
            <!-- Placeholder for image -->
            <div class="h-48 bg-gray-300 dark:bg-gray-700"></div>

            <!-- Placeholder for content -->
            <div class="p-6">
                <!-- Title placeholder -->
                <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded w-3/4 mb-4"></div>

                <!-- Room details placeholder -->
                <div class="flex gap-3 mb-4">
                    <div class="h-5 bg-gray-300 dark:bg-gray-700 rounded w-1/4"></div>
                    <div class="h-5 bg-gray-300 dark:bg-gray-700 rounded w-1/4"></div>
                </div>

                <!-- Amenities placeholder -->
                <div class="mb-4">
                    <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-1/4 mb-2"></div>
                    <div class="flex flex-wrap gap-2">
                        <div class="h-5 bg-gray-300 dark:bg-gray-700 rounded w-16"></div>
                        <div class="h-5 bg-gray-300 dark:bg-gray-700 rounded w-16"></div>
                        <div class="h-5 bg-gray-300 dark:bg-gray-700 rounded w-16"></div>
                    </div>
                </div>

                <!-- Description placeholder -->
                <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-full mb-2"></div>
                <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-4/5 mb-4"></div>

                <!-- Price & button placeholders -->
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded w-20 mb-1"></div>
                        <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-24"></div>
                    </div>
                    <div class="h-9 bg-gray-300 dark:bg-gray-700 rounded w-28"></div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function loadRoomData(): void
    {
        $this->isLoading = false;
    }

    public function render()
    {
        // If the component is still loading, trigger load after a short delay
        if ($this->isLoading) {
            $this->dispatch('init-loading');
        }

        return view('livewire.room-type-card');
    }
}
