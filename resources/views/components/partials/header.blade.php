<nav class="bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600 dark:text-indigo-500">
                        {{ config('app.name') }}
                    </a>
                </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-8">
                <a href="{{ route('rooms') }}" class="font-medium transition duration-150 {{ request()->is('rooms*') ? 'text-indigo-500 dark:text-indigo-400 font-bold' : 'text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400' }}">Rooms</a>
                <a href="{{ route('amenities') }}" class="font-medium transition duration-150 {{ request()->is('amenities*') ? 'text-indigo-500 dark:text-indigo-400 font-bold' : 'text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400' }}">Amenities</a>
                <a href="#" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Gallery</a>
                <a href="{{ route('contact') }}" class="font-medium transition duration-150 {{ request()->is('contact*') ? 'text-indigo-500 dark:text-indigo-400 font-bold' : 'text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400' }}">Contact</a>

                @if (Route::has('login'))
                    @auth
                        <!-- Profile dropdown -->
                        <div 
                            x-data="{ open: false }" 
                            @keydown.escape.stop="open = false"
                            @click.away="open = false"
                            class="relative ml-3"
                        >
                            <div>
                                <button 
                                    type="button" 
                                    @click="open = !open" 
                                    class="flex items-center gap-x-1 rounded-full bg-white dark:bg-slate-700 p-1.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-600 transition duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    aria-expanded="false"
                                    aria-haspopup="true"
                                >
                                    <span class="sr-only">Open user menu</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Dropdown menu -->
                            <div 
                                x-show="open" 
                                x-transition:enter="transition ease-out duration-200" 
                                x-transition:enter-start="transform opacity-0 scale-95" 
                                x-transition:enter-end="transform opacity-100 scale-100" 
                                x-transition:leave="transition ease-in duration-75" 
                                x-transition:leave-start="transform opacity-100 scale-100" 
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-slate-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="user-menu-button"
                                tabindex="-1"
                                style="display: none;"
                            >
                                <!-- User name display -->
                                <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                                    <div class="truncate">{{ Auth::user()->email }}</div>
                                </div>
                                
                                <!-- Dropdown menu items -->
                                <a 
                                    href="{{ route('my.bookings') }}" 
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition duration-150"
                                    role="menuitem"
                                    tabindex="-1"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    My Bookings
                                </a>
                                
                                <a 
                                    href="{{ route('settings.profile') }}" 
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition duration-150"
                                    role="menuitem"
                                    tabindex="-1"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                    </svg>
                                    Settings
                                </a>
                                
                                <a 
                                    href="{{ route('settings.appearance') }}" 
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition duration-150"
                                    role="menuitem"
                                    tabindex="-1"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z" />
                                    </svg>
                                    Appearance
                                </a>
                                
                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="flex w-full items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-red-500 dark:hover:text-red-400 transition duration-150"
                                        role="menuitem"
                                        tabindex="-1"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                        </svg>
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Log in</a>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>
