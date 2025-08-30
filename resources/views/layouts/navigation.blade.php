<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <aside class="w-60 bg-white h-screen shadow-md hidden sm:block">
        <div class="h-full flex flex-col justify-between">
            <div class="p-6 space-y-4">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
            </div>
        </div>
        <!-- Logout Button -->
        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="w-full text-left">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </div>
    </aside>
</nav>
