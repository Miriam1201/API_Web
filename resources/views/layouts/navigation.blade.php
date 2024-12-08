<nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Volver a la Comunidad -->
            <div>
                <x-dropdown-link :href="route('comunidad')" class="text-white text-sm sm:text-base">
                    {{ __('Volver a la Comunidad') }}
                </x-dropdown-link>
            </div>

            <!-- Logout Link -->
            <div>
                <a href="{{ route('logout') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-700 hover:bg-gray-600 focus:outline-none transition ease-in-out duration-150"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Log Out') }}
                </a>

                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Responsive Logout Link -->
                <a href="{{ route('logout') }}"
                    class="block text-white hover:text-gray-400 focus:outline-none focus:text-white transition ease-in-out duration-150"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Log Out') }}
                </a>

                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</nav>
