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
                        <a href="{{ url('/dashboard') }}" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">My booking</a>
                    @else
                        <a href="{{ route('login') }}" class="font-medium text-slate-600 hover:text-indigo-500 dark:text-slate-300 dark:hover:text-indigo-400 transition duration-150">Log in</a>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>
