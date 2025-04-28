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
                <a href="{{ route('rooms') }}" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Rooms</a>
                <a href="#" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Amenities</a>
                <a href="#" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Gallery</a>
                <a href="{{ route('contact') }}" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Contact</a>

                {{-- @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition ease-in-out duration-150">Register</a>
                        @endif
                    @endauth
                @endif --}}
            </div>
        </div>
    </div>
</nav>
