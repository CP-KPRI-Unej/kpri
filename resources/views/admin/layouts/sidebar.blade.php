<div 
    x-cloak
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-orange-500 dark:bg-gray-800 text-white shadow-lg transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-between p-4">
            <span class="text-xl font-bold text-white">KPRI Admin</span>
            <!-- Close button (mobile only) -->
            <button 
                @click="sidebarOpen = false"
                class="p-2 rounded-md text-white/80 hover:text-white hover:bg-white/10 lg:hidden">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
        
        <div class="px-4 py-2">
            <h3 class="text-xs font-semibold tracking-wider uppercase text-white/70">MENU</h3>
        </div>
        
        <div class="flex-1 overflow-y-auto">
            <nav class="px-4 py-2 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-house-door mr-3 text-lg"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.linktree.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.linktree.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-link-45deg mr-3 text-lg"></i>
                    <span>Linktree</span>
                </a>
                
                <a href="{{ route('admin.artikel.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.artikel.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-file-text mr-3 text-lg"></i>
                    <span>Artikel</span>
                </a>
                
                <a href="{{ route('admin.struktur.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.struktur.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-diagram-3 mr-3 text-lg"></i>
                    <span>Struktur Kepengurusan</span>
                </a>
                
                <a href="{{ route('admin.download.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ Request::is('admin/download*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-file-earmark-arrow-down-fill mr-3 text-lg"></i>
                    <span>Download Items</span>
                </a>
                
                <!-- Manajemen Produk Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.produk.*') || request()->routeIs('admin.promo.*') || request()->routeIs('admin.kategori.*') ? 'true' : 'false' }} }" class="relative">
                    <!-- Main dropdown button -->
                    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium rounded-md {{ (request()->routeIs('admin.produk.*') || request()->routeIs('admin.promo.*') || request()->routeIs('admin.kategori.*')) ? 'bg-white/10 text-white dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                        <div class="flex items-center">
                            <i class="bi bi-box-seam mr-3 text-lg"></i>
                            <span>Manajemen Produk</span>
                        </div>
                        <i class="bi" :class="{'bi-chevron-down': !open, 'bi-chevron-up': open}"></i>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div x-show="open" class="pl-4 mt-1 space-y-1">
                        <a href="{{ route('admin.produk.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.produk.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                            <i class="bi bi-circle-fill mr-3 text-[8px]"></i>
                            <span>Produk</span>
                        </a>
                        <a href="{{ route('admin.promo.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.promo.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                            <i class="bi bi-circle-fill mr-3 text-[8px]"></i>
                            <span>Promo</span>
                        </a>
                        <a href="{{ route('admin.kategori.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.kategori.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                            <i class="bi bi-circle-fill mr-3 text-[8px]"></i>
                            <span>Kategori</span>
                        </a>
                    </div>
                </div>
                
                <!-- Manajemen Halaman Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.halaman.*') || request()->routeIs('admin.layanan.*') ? 'true' : 'false' }} }" class="relative">
                    <!-- Main dropdown button -->
                    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium rounded-md {{ (request()->routeIs('admin.halaman.*') || request()->routeIs('admin.layanan.*')) ? 'bg-white/10 text-white dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                        <div class="flex items-center">
                            <i class="bi bi-window mr-3 text-lg"></i>
                            <span>Manajemen Halaman</span>
                        </div>
                        <i class="bi" :class="{'bi-chevron-down': !open, 'bi-chevron-up': open}"></i>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div x-show="open" class="pl-4 mt-1 space-y-1">
                        @foreach($jenisLayanans as $jenis)
                            @php
                                $isActive = request()->route('id_jenis_layanan') == $jenis->id_jenis_layanan;
                            @endphp
                            <a href="{{ route('admin.layanan.index', $jenis->id_jenis_layanan) }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ $isActive ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                                <i class="bi {{ $isActive ? 'bi-circle-fill' : 'bi-circle' }} mr-3 text-[8px]"></i>
                                <span>{{ $jenis->nama_layanan }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <a href="{{ route('admin.galeri.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.galeri.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-images mr-3 text-lg"></i>
                    <span>Galeri Foto</span>
                </a>
                
                <a href="{{ route('admin.faq.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.faq.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-question-circle mr-3 text-lg"></i>
                    <span>FAQ</span>
                </a>
                
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-gear mr-3 text-lg"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </div>
        
        <!-- User profile -->
        <div class="p-4 border-t border-white/10 dark:border-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-person-circle text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ Auth::user()->nama_user }}</p>
                    <p class="text-xs text-white/70 dark:text-gray-300">{{ Auth::user()->role->nama_role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                    @csrf
                    <button type="submit" class="p-1 text-white/70 hover:text-white dark:text-gray-400 dark:hover:text-white">
                        <i class="bi bi-box-arrow-right text-lg"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Sidebar backdrop -->
<div 
    x-show="sidebarOpen" 
    @click="sidebarOpen = false" 
    class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity lg:hidden">
</div> 