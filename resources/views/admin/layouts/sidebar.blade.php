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
                <!-- Dashboard Link -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-house-door mr-3 text-lg"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Linktree Link -->
                <a href="{{ route('admin.linktree.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.linktree.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-link-45deg mr-3 text-lg"></i>
                    <span>Linktree</span>
                </a>

                <!-- Artikel Link -->
                <a href="{{ route('admin.artikel.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.artikel.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-file-text mr-3 text-lg"></i>
                    <span>Artikel</span>
                </a>

                <!-- Struktur Kepengurusan Link -->
                <a href="{{ route('admin.struktur.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.struktur.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-diagram-3 mr-3 text-lg"></i>
                    <span>Struktur Kepengurusan</span>
                </a>

                <!-- Download Items Link -->
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

                <!-- Layanan (Services) Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.layanan.*') || request()->routeIs('admin.hero-banners.*') ? 'true' : 'false' }} }" class="relative">
                    <!-- Main dropdown button -->
                    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.layanan.*') || request()->routeIs('admin.hero-banners.*') ? 'bg-white/10 text-white dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                        <div class="flex items-center">
                            <i class="bi bi-gear-wide-connected mr-3 text-lg"></i>
                            <span>Layanan</span>
                        </div>
                        <i class="bi" :class="{'bi-chevron-down': !open, 'bi-chevron-up': open}"></i>
                    </button>

                    <!-- Dropdown menu -->
                    <div x-show="open" class="pl-4 mt-1 space-y-1" id="layanan-menu">
                        <!-- Hero Banner as first item in the dropdown -->
                        <a href="{{ route('admin.hero-banners.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.hero-banners.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                            <i class="bi bi-image mr-3 text-[14px]"></i>
                            <span>Hero Banner</span>
                            </a>

                        <!-- Loading indicator for dynamic layanan items -->
                        <div class="text-center py-2 text-sm text-white/50">
                            <i class="bi bi-arrow-repeat animate-spin"></i> Memuat...
                        </div>
                    </div>
                </div>

                <!-- Gallery Link -->
                <a href="{{ route('admin.galeri.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.galeri.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-images mr-3 text-lg"></i>
                    <span>Galeri Foto</span>
                </a>

                <!-- FAQ Link -->
                <a href="{{ route('admin.faq.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.faq.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-question-circle mr-3 text-lg"></i>
                    <span>FAQ</span>
                </a>

                <!-- Notifications Link -->
                <a href="{{ route('admin.notification.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.notifications.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-bell mr-3 text-lg"></i>
                    <span>Notifikasi</span>
                </a>

                <!-- Settings Link -->
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.*') ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700' }}">
                    <i class="bi bi-gear mr-3 text-lg"></i>
                    <span>Pengaturan</span>
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
                    <p class="text-sm font-medium text-white" id="sidebar-user-name">Loading...</p>
                    <p class="text-xs text-white/70 dark:text-gray-300" id="sidebar-user-role">Loading...</p>
                </div>
                <button id="logout-button" class="ml-auto p-1 text-white/70 hover:text-white dark:text-gray-400 dark:hover:text-white">
                        <i class="bi bi-box-arrow-right text-lg"></i>
                    </button>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            // Redirect to login if no token found
            window.location.href = '/admin/login';
            return;
        }

        // Get user data from API
        fetch('/api/auth/me', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    // Unauthorized, clear token and redirect to login
                    localStorage.removeItem('access_token');
                    window.location.href = '/admin/login';
                    return null;
                }
                throw new Error('Failed to fetch user data');
            }
            return response.json();
        })
        .then(data => {
            if (!data) return;

            // Update sidebar user info
            document.getElementById('sidebar-user-name').textContent = data.user.nama_user;
            document.getElementById('sidebar-user-role').textContent = data.role;
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
        });

        // Fetch jenis layanan for dropdown menu
        fetch('/api/admin/layanan/jenis', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch service types');
            }
            return response.json();
        })
        .then(data => {
            if (!data || !data.data) return;

            // Build the layanan menu
            const jenisLayanans = data.data;
            const menuContainer = document.getElementById('layanan-menu');

            // Save Hero Banner link before clearing
            const heroBannerLink = menuContainer.querySelector('a[href*="hero-banners"]');

            // Clear loading placeholder but keep the Hero Banner link
            menuContainer.innerHTML = '';

            // Re-add the Hero Banner link at the top
            if (heroBannerLink) {
                menuContainer.appendChild(heroBannerLink);
            }

            // Get current path to determine active state
            const currentPath = window.location.pathname;
            const urlParams = new URLSearchParams(window.location.search);
            const currentId = urlParams.get('id_jenis_layanan') ||
                              currentPath.match(/\/layanan\/(\d+)$/)?.[1];

            // Add each jenis layanan to the menu
            jenisLayanans.forEach(jenis => {
                const isActive = currentId == jenis.id_jenis_layanan;
                const menuItem = document.createElement('a');
                menuItem.href = '#';
                menuItem.className = `flex items-center px-4 py-2 text-sm font-medium rounded-md ${isActive ? 'bg-white text-orange-500 dark:bg-gray-700 dark:text-gray-100' : 'text-white/80 hover:bg-white/10 hover:text-white dark:hover:bg-gray-700'}`;

                menuItem.innerHTML = `
                    <i class="bi ${isActive ? 'bi-circle-fill' : 'bi-circle'} mr-3 text-[8px]"></i>
                    <span>${jenis.nama_layanan}</span>
                `;

                // Add click event listener to fetch first layanan and redirect
                menuItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetchFirstLayananAndRedirect(jenis.id_jenis_layanan);
                });

                menuContainer.appendChild(menuItem);
            });
        })
        .catch(error => {
            console.error('Error fetching jenis layanan:', error);
            const menuContainer = document.getElementById('layanan-menu');
            menuContainer.innerHTML = '<div class="text-center py-2 text-sm text-red-300">Gagal memuat data</div>';
        });

        // New function to fetch the first layanan and redirect to its edit page
        function fetchFirstLayananAndRedirect(jenisLayananId) {
            fetch(`/api/admin/layanan/${jenisLayananId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch layanan data');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success' && data.data && data.data.length > 0) {
                    // Redirect to the edit page of the first layanan
                    const firstLayanan = data.data[0];
                    window.location.href = `/admin/layanan/edit/${firstLayanan.id_layanan}`;
                } else {
                    // If no layanan exists, redirect to the index page
                    window.location.href = `/admin/layanan/${jenisLayananId}`;
                }
            })
            .catch(error => {
                console.error('Error fetching first layanan:', error);
                // Fallback to index page on error
                window.location.href = `/admin/layanan/${jenisLayananId}`;
            });
        }

        // Handle logout
        document.getElementById('logout-button').addEventListener('click', function() {
            fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                // Clear token and redirect regardless of response
                localStorage.removeItem('access_token');
                window.location.href = '/admin/login';
            })
            .catch(error => {
                console.error('Error during logout:', error);
                // Still clear token and redirect on error
                localStorage.removeItem('access_token');
                window.location.href = '/admin/login';
            });
        });
    });
</script>
