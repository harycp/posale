@php
    $role = Auth::user()->role;
@endphp

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

                <div class="h-1"></div>

                <div class="mb-4">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center space-x-3 group w-full p-2 rounded-md hover:bg-gray-100 transition duration-150 ease-in-out">
                        <div class="relative">
                            <div
                                class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span
                                class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                        </div>
                        <div>
                            <p
                                class="font-semibold text-sm text-gray-800 group-hover:text-blue-600 transition duration-150 ease-in-out">
                                {{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">Online</p>
                        </div>
                    </a>
                </div>

                @if ($role == 'admin')
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();" class="w-full text-left">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @endif
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Kasir') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cashier.products.index')" :active="request()->routeIs('cashier.products.*')">
                    {{ __('Data Produk') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="#">
                    {{ __('Riwayat Transaksi') }}
                </x-responsive-nav-link>

            </div>
        </div>

    </aside>
</nav>
