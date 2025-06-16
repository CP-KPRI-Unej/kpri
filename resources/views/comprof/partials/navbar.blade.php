<nav class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('beranda') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="KPRI UNEJ Logo" class="h-10 w-10 mr-2">
                    <span class="font-bold text-gray-900 dark:text-white">KPRI UNIVERSITAS JEMBER</span>
                </a>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-6">
                <a href="{{ route('beranda') }}"
                    class="{{ request()->routeIs('beranda') ? 'text-amber-500' : 'text-gray-700 dark:text-gray-300' }} hover:text-amber-500 dark:hover:text-amber-400 px-3 py-2 text-sm font-medium">
                    Beranda
                </a>
                <a href="{{ route('tentang-kami') }}"
                    class="{{ request()->routeIs('tentang-kami') ? 'text-amber-500' : 'text-gray-700 dark:text-gray-300' }} hover:text-amber-500 dark:hover:text-amber-400 px-3 py-2 text-sm font-medium">
                    Tentang Kami
                </a>
                <a href="{{ route('gerai-layanan') }}"
                    class="{{ request()->routeIs('gerai-layanan') ? 'text-amber-500' : 'text-gray-700 dark:text-gray-300' }} hover:text-amber-500 dark:hover:text-amber-400 px-3 py-2 text-sm font-medium">
                    Gerai Layanan
                </a>
                <a href="{{ route('unit-simpan-pinjam') }}"
                    class="{{ request()->routeIs('unit-simpan-pinjam') ? 'text-amber-500' : 'text-gray-700 dark:text-gray-300' }} hover:text-amber-500 dark:hover:text-amber-400 px-3 py-2 text-sm font-medium">
                    Unit Simpan Pinjam
                </a>
                <a href="{{ route('unit-jasa') }}"
                    class="{{ request()->routeIs('unit-jasa') ? 'text-amber-500' : 'text-gray-700 dark:text-gray-300' }} hover:text-amber-500 dark:hover:text-amber-400 px-3 py-2 text-sm font-medium">
                    Unit Jasa
                </a>
                <a href="{{ route('unit-toko') }}"
                    class="{{ request()->routeIs('unit-toko') ? 'text-amber-500' : 'text-gray-700 dark:text-gray-300' }} hover:text-amber-500 dark:hover:text-amber-400 px-3 py-2 text-sm font-medium">
                    Unit Toko
                </a>
                <a href="{{ route('toko.index') }}"
                    class="{{ request()->routeIs('toko.index') || request()->routeIs('toko.show') ? 'text-amber-500' : 'text-gray-700 dark:text-gray-300' }} hover:text-amber-500 dark:hover:text-amber-400 px-3 py-2 text-sm font-medium">
                    Katalog Produk
                </a>

                <button id="cart-button" class="p-1 rounded-full text-gray-700 dark:text-gray-300 hover:text-amber-500 dark:hover:text-amber-400 focus:outline-none relative">
                    <span class="sr-only">Keranjang Belanja</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span id="cart-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center hidden">0</span>
                </button>

                <button onclick="toggleDarkMode()"
                    class="p-1 rounded-full text-gray-700 dark:text-gray-300 hover:text-amber-500 dark:hover:text-amber-400 focus:outline-none">
                    <span class="sr-only">Toggle dark mode</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden dark:block" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block dark:hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>

            <div class="flex md:hidden items-center">
                <button type="button"
                    class="text-gray-700 dark:text-gray-300 hover:text-amber-500 dark:hover:text-amber-400"
                    x-data="{}" @click="$dispatch('toggle-mobile-menu')">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden" x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('beranda') }}"
                class="{{ request()->routeIs('beranda') ? 'bg-amber-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Beranda
            </a>
            <a href="{{ route('tentang-kami') }}"
                class="{{ request()->routeIs('tentang-kami') ? 'bg-amber-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Tentang Kami
            </a>
            <a href="{{ route('gerai-layanan') }}"
                class="{{ request()->routeIs('gerai-layanan') ? 'bg-amber-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Gerai Layanan
            </a>
            <a href="{{ route('unit-simpan-pinjam') }}"
                class="{{ request()->routeIs('unit-simpan-pinjam') ? 'bg-amber-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Unit Simpan Pinjam
            </a>
            <a href="{{ route('unit-jasa') }}"
                class="{{ request()->routeIs('unit-jasa') ? 'bg-amber-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Unit Jasa
            </a>
            <a href="{{ route('unit-toko') }}"
                class="{{ request()->routeIs('unit-toko') ? 'bg-amber-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Unit Toko
            </a>
            <a href="{{ route('toko.index') }}"
                class="{{ request()->routeIs('toko.index') || request()->routeIs('toko.show') ? 'bg-amber-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Katalog Produk
            </a>
            <a href="{{ route('members') }}"
                class="{{ request()->routeIs('members') ? 'bg-amber-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium">
                Info Anggota
            </a>
            <button id="mobile-cart-button" class="w-full text-left text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Keranjang Belanja</span>
                    <span id="mobile-cart-badge" class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center hidden">0</span>
                </div>
            </button>
            <button onclick="toggleDarkMode()"
                class="w-full text-left text-gray-700 dark:text-gray-300 hover:bg-amber-500 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 hidden dark:inline" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline dark:hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span class="dark:hidden">Mode Gelap</span>
                    <span class="hidden dark:inline">Mode Terang</span>
                </div>
            </button>
        </div>
    </div>
</nav>
