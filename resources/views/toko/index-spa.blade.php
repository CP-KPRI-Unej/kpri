@extends('toko.layouts.app')

@section('title', 'Katalog Produk KPRI')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-amber-500 to-amber-700 text-white py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-3xl font-bold mb-3">Katalog Produk KPRI</h1>
                <p class="text-amber-100 text-lg mb-6">Temukan beragam produk berkualitas dengan harga terbaik untuk kebutuhan sehari-hari Anda.</p>
                <div class="relative max-w-md">
                    <input type="text" id="hero-search" class="w-full px-4 py-3 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-300 focus:outline-none" placeholder="Cari produk...">
                    <button id="hero-search-btn" class="absolute right-0 top-0 h-full px-4 bg-amber-800 hover:bg-amber-900 rounded-r-lg text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Katalog Produk KPRI" class="h-48 md:h-64 object-contain" >
            </div>
        </div>
    </div>
</div>

<!-- Promotions Section -->
<div class="bg-amber-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Promo Spesial</h2>
            <a href="{{ route('toko.index') }}?promo_only=true" class="text-amber-600 dark:text-amber-400 hover:underline font-medium flex items-center">
                Lihat Semua
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
        
        <div class="relative">
            <!-- Slider Navigation -->
            <div class="absolute top-1/2 left-0 transform -translate-y-1/2 -ml-4 z-10 hidden md:block">
                <button id="promo-prev" class="p-2 rounded-full bg-white dark:bg-gray-700 shadow-md text-amber-500 hover:bg-amber-50 dark:hover:bg-gray-600 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>
            <div class="absolute top-1/2 right-0 transform -translate-y-1/2 -mr-4 z-10 hidden md:block">
                <button id="promo-next" class="p-2 rounded-full bg-white dark:bg-gray-700 shadow-md text-amber-500 hover:bg-amber-50 dark:hover:bg-gray-600 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            
            <!-- Promo Products Slider -->
            <div id="promo-slider" class="overflow-hidden">
                <div id="promo-slider-track" class="flex space-x-4 transition-transform duration-300 ease-out">
                    <!-- Loading placeholders - will be replaced by JavaScript -->
                    <div class="animate-pulse bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden min-w-[250px] w-[250px] flex-shrink-0">
                        <div class="h-40 bg-gray-200 dark:bg-gray-700"></div>
                        <div class="p-4">
                            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-3"></div>
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                        </div>
                    </div>
                    <div class="animate-pulse bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden min-w-[250px] w-[250px] flex-shrink-0">
                        <div class="h-40 bg-gray-200 dark:bg-gray-700"></div>
                        <div class="p-4">
                            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-3"></div>
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                        </div>
                    </div>
                    <div class="animate-pulse bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden min-w-[250px] w-[250px] flex-shrink-0">
                        <div class="h-40 bg-gray-200 dark:bg-gray-700"></div>
                        <div class="p-4">
                            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-3"></div>
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Pagination Dots -->
            <div class="flex justify-center mt-4 md:hidden">
                <div id="promo-dots" class="flex space-x-2">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar: Categories & Filters -->
        <div class="md:w-1/4 lg:w-1/5 order-2 md:order-1">
            <!-- Categories -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
                <h2 class="font-semibold text-gray-800 dark:text-white text-lg mb-3">Kategori</h2>
                <div id="categories-container" class="space-y-2">
                    <div class="animate-pulse">
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2.5"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-2.5"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3 mb-2.5"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                    </div>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                <h2 class="font-semibold text-gray-800 dark:text-white text-lg mb-3">Filter</h2>
                
                <!-- Search -->
                <div class="mb-4">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Produk</label>
                    <div class="relative">
                        <input type="text" id="search-input" class="block w-full pr-10 rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Nama produk...">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Sort -->
                <div class="mb-4">
                    <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Urutkan</label>
                    <select id="sort-select" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="nama_produk,asc">Nama (A-Z)</option>
                        <option value="nama_produk,desc">Nama (Z-A)</option>
                        <option value="harga_produk,asc">Harga (Terendah)</option>
                        <option value="harga_produk,desc">Harga (Tertinggi)</option>
                    </select>
                </div>
                
                <!-- Promo Only Filter -->
                <div class="mb-4">
                    <div class="flex items-center">
                        <input id="promo-only" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500 dark:border-gray-600">
                        <label for="promo-only" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Hanya Tampilkan Promo
                        </label>
                    </div>
                </div>
                
                <!-- Apply Filters Button -->
                <button type="button" id="apply-filters" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-4 rounded-md text-sm transition">
                    Terapkan Filter
                </button>
            </div>
        </div>
        
        <!-- Main Content: Products -->
        <div class="md:w-3/4 lg:w-4/5 order-1 md:order-2">
            <!-- Products -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                <div class="flex flex-wrap items-center justify-between mb-4">
                    <h2 class="font-semibold text-gray-800 dark:text-white text-lg">Semua Produk</h2>
                    <div id="products-count" class="text-sm text-gray-500 dark:text-gray-400">Loading...</div>
                </div>
                
                <div id="products-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Loading placeholders -->
                    <div class="animate-pulse bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                        <div class="h-48 bg-gray-200 dark:bg-gray-600 rounded-t-lg"></div>
                        <div class="p-3">
                            <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-1/2 mb-3"></div>
                            <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded w-1/4"></div>
                        </div>
                    </div>
                    <div class="animate-pulse bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                        <div class="h-48 bg-gray-200 dark:bg-gray-600 rounded-t-lg"></div>
                        <div class="p-3">
                            <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-1/2 mb-3"></div>
                            <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded w-1/4"></div>
                        </div>
                    </div>
                    <div class="animate-pulse bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                        <div class="h-48 bg-gray-200 dark:bg-gray-600 rounded-t-lg"></div>
                        <div class="p-3">
                            <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-1/2 mb-3"></div>
                            <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded w-1/4"></div>
                        </div>
                    </div>
                    <div class="animate-pulse bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                        <div class="h-48 bg-gray-200 dark:bg-gray-600 rounded-t-lg"></div>
                        <div class="p-3">
                            <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-1/2 mb-3"></div>
                            <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded w-1/4"></div>
                        </div>
                    </div>
                </div>
                
                <!-- No Results Message -->
                <div id="no-results" class="hidden text-center py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-1">Tidak ada produk ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Coba ubah filter pencarian Anda</p>
                    <button id="reset-filter" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-md text-sm font-medium transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
                
                <!-- Pagination -->
                <div id="pagination" class="mt-6 flex justify-center">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // State
    let state = {
        products: [],
        categories: [],
        promoProducts: [], // Store all promo products here
        filters: {
            category: null,
            search: '',
            sort: 'nama_produk,asc',
            promoOnly: false,
            page: 1
        },
        isLoading: true,
        promoSlider: {
            currentIndex: 0,
            itemsPerPage: 4,
            totalPages: 0
        }
    };
    
    // DOM Elements
    const categoriesContainer = document.getElementById('categories-container');
    const promoSliderTrack = document.getElementById('promo-slider-track');
    const promoPrevBtn = document.getElementById('promo-prev');
    const promoNextBtn = document.getElementById('promo-next');
    const promoDots = document.getElementById('promo-dots');
    const productsContainer = document.getElementById('products-container');
    const productsCount = document.getElementById('products-count');
    const searchInput = document.getElementById('search-input');
    const heroSearchInput = document.getElementById('hero-search');
    const sortSelect = document.getElementById('sort-select');
    const promoOnlyCheckbox = document.getElementById('promo-only');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const resetFilterBtn = document.getElementById('reset-filter');
    const noResults = document.getElementById('no-results');
    const pagination = document.getElementById('pagination');
    const heroSearchBtn = document.getElementById('hero-search-btn');
    
    // Check URL for promo_only parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('promo_only') && urlParams.get('promo_only') === 'true') {
        state.filters.promoOnly = true;
        promoOnlyCheckbox.checked = true;
    }
    
    // Fetch Initial Data
    fetchCategories();
    fetchAllPromoProducts();
    fetchProducts();
    
    // Event Listeners
    applyFiltersBtn.addEventListener('click', function() {
        state.filters.search = searchInput.value;
        state.filters.sort = sortSelect.value;
        state.filters.promoOnly = promoOnlyCheckbox.checked;
        state.filters.page = 1;
        fetchProducts();
    });
    
    resetFilterBtn.addEventListener('click', function() {
        state.filters.category = null;
        state.filters.search = '';
        state.filters.sort = 'nama_produk,asc';
        state.filters.promoOnly = false;
        state.filters.page = 1;
        
        // Reset UI
        searchInput.value = '';
        heroSearchInput.value = '';
        sortSelect.value = 'nama_produk,asc';
        promoOnlyCheckbox.checked = false;
        
        // Unselect all category buttons
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('bg-amber-100', 'text-amber-800', 'dark:bg-amber-900', 'dark:text-amber-200');
            btn.classList.add('bg-gray-100', 'text-gray-800', 'dark:bg-gray-700', 'dark:text-gray-200');
        });
        
        fetchProducts();
    });
    
    heroSearchBtn.addEventListener('click', function() {
        searchInput.value = heroSearchInput.value;
        state.filters.search = heroSearchInput.value;
        state.filters.page = 1;
        fetchProducts();
    });
    
    heroSearchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchInput.value = heroSearchInput.value;
            state.filters.search = heroSearchInput.value;
            state.filters.page = 1;
            fetchProducts();
        }
    });
    
    // Slider navigation
    promoPrevBtn.addEventListener('click', function() {
        navigatePromoSlider(-1);
    });
    
    promoNextBtn.addEventListener('click', function() {
        navigatePromoSlider(1);
    });
    
    // Functions
    function fetchCategories() {
        fetch('/api/shop/categories')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    state.categories = data.data;
                    renderCategories();
                }
            })
            .catch(error => {
                console.error('Error fetching categories:', error);
                categoriesContainer.innerHTML = '<p class="text-red-500 text-sm">Failed to load categories</p>';
            });
    }
    
    function fetchAllPromoProducts() {
        fetch('/api/shop/promotions')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    // Extract all products from all promotions
                    const allPromoProducts = [];
                    data.data.forEach(promo => {
                        promo.produks.forEach(product => {
                            // Add promotion details to each product
                            product.promo = {
                                id: promo.id,
                                judul: promo.judul,
                                tipe_diskon: promo.tipe_diskon,
                                nilai_diskon: promo.nilai_diskon,
                                tgl_end: promo.tgl_end
                            };
                            allPromoProducts.push(product);
                        });
                    });
                    
                    state.promoProducts = allPromoProducts;
                    renderPromoSlider();
                }
            })
            .catch(error => {
                console.error('Error fetching promotions:', error);
                promoSliderTrack.innerHTML = '<p class="text-red-500 text-sm p-4">Failed to load promotional products</p>';
            });
    }
    
    function fetchProducts() {
        state.isLoading = true;
        renderProductsLoading();
        
        // Build query params
        const params = new URLSearchParams();
        if (state.filters.category) params.append('kategori_id', state.filters.category);
        if (state.filters.search) params.append('search', state.filters.search);
        
        const [sortField, sortDir] = state.filters.sort.split(',');
        params.append('order_by', sortField);
        params.append('order_dir', sortDir);
        
        fetch(`/api/shop/products?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                state.isLoading = false;
                
                if (data.success && data.data) {
                    // If promo only filter is applied, filter products with promos
                    if (state.filters.promoOnly) {
                        state.products = data.data.filter(product => product.has_promo);
                    } else {
                        state.products = data.data;
                    }
                    
                    renderProducts();
                    updateProductsCount();
                }
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                state.isLoading = false;
                productsContainer.innerHTML = '<p class="text-red-500 text-center col-span-full py-10">Failed to load products. Please try again later.</p>';
            });
    }
    
    function renderCategories() {
        let html = `
            <div class="category-item mb-2">
                <button class="category-btn w-full text-left px-3 py-2 rounded-md bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 hover:bg-amber-200 dark:hover:bg-amber-800 transition"
                    data-category="">
                    Semua Kategori
                </button>
            </div>
        `;
        
        state.categories.forEach(category => {
            html += `
                <div class="category-item mb-2">
                    <button class="category-btn w-full text-left px-3 py-2 rounded-md bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                        data-category="${category.id}">
                        ${category.nama}
                        <span class="float-right text-xs text-gray-500 dark:text-gray-400">${category.jumlah_produk}</span>
                    </button>
                </div>
            `;
        });
        
        categoriesContainer.innerHTML = html;
        
        // Add event listeners to category buttons
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const categoryId = this.dataset.category || null;
                
                // Update state
                state.filters.category = categoryId;
                state.filters.page = 1;
                
                // Update UI
                document.querySelectorAll('.category-btn').forEach(btn => {
                    btn.classList.remove('bg-amber-100', 'text-amber-800', 'dark:bg-amber-900', 'dark:text-amber-200');
                    btn.classList.add('bg-gray-100', 'text-gray-800', 'dark:bg-gray-700', 'dark:text-gray-200');
                });
                
                this.classList.remove('bg-gray-100', 'text-gray-800', 'dark:bg-gray-700', 'dark:text-gray-200');
                this.classList.add('bg-amber-100', 'text-amber-800', 'dark:bg-amber-900', 'dark:text-amber-200');
                
                // Fetch filtered products
                fetchProducts();
            });
        });
    }
    
    function renderPromoSlider() {
        if (state.promoProducts.length === 0) {
            promoSliderTrack.innerHTML = `
                <div class="w-full text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada produk promo aktif saat ini.</p>
                </div>
            `;
            promoPrevBtn.classList.add('hidden');
            promoNextBtn.classList.add('hidden');
            return;
        }
        
        // Calculate how many items per page based on screen size
        // We'll set this in the CSS with a fixed width
        state.promoSlider.itemsPerPage = window.innerWidth < 768 ? 1 : 
                                         window.innerWidth < 1024 ? 2 : 
                                         window.innerWidth < 1280 ? 3 : 4;
        
        // Calculate total pages
        state.promoSlider.totalPages = Math.ceil(state.promoProducts.length / state.promoSlider.itemsPerPage);
        
        // Generate product cards for the slider
        let html = '';
        state.promoProducts.forEach(product => {
            // Calculate discount percentage
            let discountPercentage = '';
            if (product.promo.tipe_diskon === 'persen') {
                discountPercentage = product.promo.nilai_diskon;
            } else {
                // Calculate percentage for nominal discount
                discountPercentage = Math.round((product.promo.nilai_diskon / product.harga_asli) * 100);
            }
            
            html += `
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden min-w-[250px] w-[250px] flex-shrink-0 group relative border border-amber-100 dark:border-amber-900 hover:shadow-lg transition">
                    <a href="/toko/produk/${product.id}" class="block">
                        <div class="relative h-40 overflow-hidden bg-gray-100 dark:bg-gray-800">
                            <img src="${product.gambar || '/images/no-image.png'}" 
                                alt="${product.nama}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                ${discountPercentage}% OFF
                            </div>
                            
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent text-white py-2 px-3">
                                <p class="text-xs font-medium line-clamp-1">
                                    ${product.promo.judul}
                                </p>
                            </div>
                            
                            <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                <a href="/toko/produk/${product.id}" class="bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium hover:bg-amber-600 transition">Lihat Detail</a>
                                <button class="quick-add-to-cart bg-white text-amber-500 px-3 py-1 rounded-full text-sm font-medium hover:bg-gray-100 transition flex items-center" data-id="${product.id}" data-name="${product.nama}" data-price="${product.harga_diskon}" data-image="${product.gambar || '/images/no-image.png'}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-3">
                            <h3 class="text-sm font-medium text-gray-800 dark:text-white mb-1 line-clamp-2 h-10">${product.nama}</h3>
                            
                            <div class="flex justify-between items-end mt-2">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 line-through">
                                        Rp ${formatNumber(product.harga_asli)}
                                    </span>
                                    <span class="text-sm font-semibold text-red-500">
                                        Rp ${formatNumber(product.harga_diskon)}
                                    </span>
                                </div>
                                
                                <span class="text-xs bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100 px-1.5 py-0.5 rounded">
                                    ${Math.ceil((new Date(product.promo.tgl_end) - new Date()) / (1000 * 60 * 60 * 24))} hari
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            `;
        });
        
        promoSliderTrack.innerHTML = html;
        
        // Add event listeners to quick add to cart buttons
        promoSliderTrack.querySelectorAll('.quick-add-to-cart').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = parseInt(this.dataset.id);
                const productName = this.dataset.name;
                const productPrice = parseInt(this.dataset.price);
                const productImage = this.dataset.image;
                
                // Create product object for cart
                const cartProduct = {
                    id: productId,
                    name: productName,
                    price: productPrice,
                    image: productImage,
                    quantity: 1
                };
                
                // Dispatch custom event to add to cart
                window.dispatchEvent(new CustomEvent('add-to-cart', {
                    detail: cartProduct
                }));
                
                // Show success message
                showQuickAddSuccess(productName);
            });
        });
        
        // Generate pagination dots for mobile
        renderPromoDots();
        
        // Initialize slider position
        navigatePromoSlider(0);
        
        // Show/hide navigation buttons
        updatePromoNavButtons();
    }
    
    function renderPromoDots() {
        if (state.promoSlider.totalPages <= 1) {
            promoDots.innerHTML = '';
            return;
        }
        
        let dotsHtml = '';
        for (let i = 0; i < state.promoSlider.totalPages; i++) {
            dotsHtml += `
                <button class="promo-dot w-2 h-2 rounded-full ${i === state.promoSlider.currentIndex ? 'bg-amber-500' : 'bg-gray-300 dark:bg-gray-600'}" 
                    data-index="${i}"></button>
            `;
        }
        
        promoDots.innerHTML = dotsHtml;
        
        // Add event listeners to dots
        document.querySelectorAll('.promo-dot').forEach(dot => {
            dot.addEventListener('click', function() {
                const index = parseInt(this.dataset.index);
                state.promoSlider.currentIndex = index;
                navigatePromoSlider(0); // Just update the position
            });
        });
    }
    
    function navigatePromoSlider(direction) {
        // Update current index
        state.promoSlider.currentIndex += direction;
        
        // Boundary checks
        if (state.promoSlider.currentIndex < 0) {
            state.promoSlider.currentIndex = 0;
        } else if (state.promoSlider.currentIndex >= state.promoSlider.totalPages) {
            state.promoSlider.currentIndex = state.promoSlider.totalPages - 1;
        }
        
        // Calculate translation distance
        const itemWidth = 250 + 16; // Card width + margin
        const translateX = -1 * state.promoSlider.currentIndex * state.promoSlider.itemsPerPage * itemWidth;
        
        // Apply transformation
        promoSliderTrack.style.transform = `translateX(${translateX}px)`;
        
        // Update dots
        document.querySelectorAll('.promo-dot').forEach((dot, index) => {
            if (index === state.promoSlider.currentIndex) {
                dot.classList.remove('bg-gray-300', 'dark:bg-gray-600');
                dot.classList.add('bg-amber-500');
            } else {
                dot.classList.remove('bg-amber-500');
                dot.classList.add('bg-gray-300', 'dark:bg-gray-600');
            }
        });
        
        // Update navigation buttons
        updatePromoNavButtons();
    }
    
    function updatePromoNavButtons() {
        // Disable prev button if at the beginning
        if (state.promoSlider.currentIndex === 0) {
            promoPrevBtn.classList.add('opacity-50', 'cursor-not-allowed');
            promoPrevBtn.disabled = true;
        } else {
            promoPrevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            promoPrevBtn.disabled = false;
        }
        
        // Disable next button if at the end
        if (state.promoSlider.currentIndex >= state.promoSlider.totalPages - 1) {
            promoNextBtn.classList.add('opacity-50', 'cursor-not-allowed');
            promoNextBtn.disabled = true;
        } else {
            promoNextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            promoNextBtn.disabled = false;
        }
    }
    
    function renderProductsLoading() {
        productsContainer.innerHTML = `
            <div class="animate-pulse bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                <div class="h-48 bg-gray-200 dark:bg-gray-600 rounded-t-lg"></div>
                <div class="p-3">
                    <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-3/4 mb-2"></div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-1/2 mb-3"></div>
                    <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded w-1/4"></div>
                </div>
            </div>
        `.repeat(8); // Show 8 loading placeholders
    }
    
    function renderProducts() {
        if (state.products.length === 0) {
            productsContainer.innerHTML = '';
            noResults.classList.remove('hidden');
            return;
        }
        
        noResults.classList.add('hidden');
        
        let html = '';
        state.products.forEach(product => {
            html += `
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition group">
                    <a href="/toko/produk/${product.id}" class="block">
                        <div class="relative aspect-w-1 aspect-h-1 overflow-hidden bg-gray-100 dark:bg-gray-800">
                            <img src="${product.gambar || '/images/no-image.png'}" 
                                alt="${product.nama}" 
                                class="object-cover w-full h-48 group-hover:scale-105 transition duration-300">
                            
                            ${product.has_promo ? `
                                <div class="absolute top-0 left-0 bg-red-500 text-white text-xs font-bold px-2 py-1 m-2 rounded-full">
                                    ${product.promo.tipe_diskon === 'persen' ? `${product.promo.nilai_diskon}% OFF` : 'DISKON'}
                                </div>
                            ` : ''}
                            
                            <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                <a href="/toko/produk/${product.id}" class="bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium hover:bg-amber-600 transition">Lihat Detail</a>
                                <button class="quick-add-to-cart bg-white text-amber-500 px-3 py-1 rounded-full text-sm font-medium hover:bg-gray-100 transition flex items-center" data-id="${product.id}" data-name="${product.nama}" data-price="${product.has_promo ? product.harga_diskon : product.harga}" data-image="${product.gambar || '/images/no-image.png'}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-3">
                            <h3 class="text-sm font-medium text-gray-800 dark:text-white mb-1 line-clamp-2 h-10">${product.nama}</h3>
                            
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                ${product.kategori.nama || 'Tanpa Kategori'}
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    ${product.has_promo ? `
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-500 dark:text-gray-400 line-through">
                                                Rp ${formatNumber(product.harga)}
                                            </span>
                                            <span class="text-sm font-semibold text-red-500">
                                                Rp ${formatNumber(product.harga_diskon)}
                                            </span>
                                        </div>
                                    ` : `
                                        <span class="text-sm font-semibold text-gray-800 dark:text-white">
                                            Rp ${formatNumber(product.harga)}
                                        </span>
                                    `}
                                </div>
                                
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Stok: ${product.stok}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            `;
        });
        
        productsContainer.innerHTML = html;
        
        // Add event listeners to quick add to cart buttons
        productsContainer.querySelectorAll('.quick-add-to-cart').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = parseInt(this.dataset.id);
                const productName = this.dataset.name;
                const productPrice = parseInt(this.dataset.price);
                const productImage = this.dataset.image;
                
                // Create product object for cart
                const cartProduct = {
                    id: productId,
                    name: productName,
                    price: productPrice,
                    image: productImage,
                    quantity: 1
                };
                
                // Dispatch custom event to add to cart
                window.dispatchEvent(new CustomEvent('add-to-cart', {
                    detail: cartProduct
                }));
                
                // Show success message
                showQuickAddSuccess(productName);
            });
        });
    }
    
    function updateProductsCount() {
        productsCount.textContent = `${state.products.length} produk`;
    }
    
    // Helper function to format numbers with thousand separators
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Helper function to show quick add success message
    function showQuickAddSuccess(productName) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50 flex items-center';
        toast.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>${productName} ditambahkan ke keranjang</span>
        `;
        
        // Add to document
        document.body.appendChild(toast);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    // Handle window resize for responsive slider
    window.addEventListener('resize', function() {
        if (state.promoProducts.length > 0) {
            // Recalculate items per page
            const oldItemsPerPage = state.promoSlider.itemsPerPage;
            state.promoSlider.itemsPerPage = window.innerWidth < 768 ? 1 : 
                                            window.innerWidth < 1024 ? 2 : 
                                            window.innerWidth < 1280 ? 3 : 4;
            
            if (oldItemsPerPage !== state.promoSlider.itemsPerPage) {
                // Update total pages
                state.promoSlider.totalPages = Math.ceil(state.promoProducts.length / state.promoSlider.itemsPerPage);
                
                // Reset current index if it's out of bounds
                if (state.promoSlider.currentIndex >= state.promoSlider.totalPages) {
                    state.promoSlider.currentIndex = state.promoSlider.totalPages - 1;
                }
                
                // Render dots and update navigation
                renderPromoDots();
                navigatePromoSlider(0);
            }
        }
    });
});
</script>
@endpush 