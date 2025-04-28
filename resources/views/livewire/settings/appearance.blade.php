<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layout')] class extends Component {
    public string $appearance = 'system';

    public function mount(): void
    {
        $this->appearance = session('appearance', 'system');
    }

    public function updatedAppearance(string $value): void
    {
        session(['appearance' => $value]);
        $this->dispatch('appearance-changed', mode: $value);
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <div
            x-data="theme"
            x-init="$watch('appearance', value => updateAppearance(value))"
            class="inline-flex items-center p-1 bg-gray-100 dark:bg-slate-800 rounded-lg"
        >
            <!-- Light Mode -->
            <button
                type="button"
                @click="setAppearance('light')"
                :class="{ 'bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm': appearance === 'light', 'text-gray-500 dark:text-gray-400': appearance !== 'light' }"
                class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 ease-in-out"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-50 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                </svg>
                {{ __('Light') }}
            </button>

            <!-- Dark Mode -->
            <button
                type="button"
                @click="setAppearance('dark')"
                :class="{ 'bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm': appearance === 'dark', 'text-gray-500 dark:text-gray-400': appearance !== 'dark' }"
                class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 ease-in-out"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-50 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                </svg>
                {{ __('Dark') }}
            </button>

            <!-- System Mode -->
            <button
                type="button"
                @click="setAppearance('system')"
                :class="{ 'bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm': appearance === 'system', 'text-gray-500 dark:text-gray-400': appearance !== 'system' }"
                class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 ease-in-out"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-50 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                </svg>
                {{ __('System') }}
            </button>
        </div>
    </x-settings.layout>
</section>
