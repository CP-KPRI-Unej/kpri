@extends('toko.layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="bg-gradient-to-r from-amber-500 to-amber-700 text-white py-6">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-sm" id="breadcrumbs">
            <a href="{{ route('toko.index') }}" class="hover:underline">Katalog</a>
            <span>/</span>
            <span class="truncate max-w-xs">Loading...</span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div id="product-loading" class="animate-pulse p-6">
            <div class="md:flex">
                <!-- Product Image Loading -->
                <div class="md:w-2/5 mb-6 md:mb-0">
                    <div class="h-80 bg-gray-200 dark:bg-gray-600 rounded-lg"></div>
                </div>
                
                <!-- Product Details Loading -->
                <div class="md:w-3/5 md:pl-6">
                    <div class="h-8 bg-gray-200 dark:bg-gray-600 rounded-lg w-3/4 mb-4"></div>
                    <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-lg w-1/2 mb-6"></div>
                    
                    <div class="h-6 bg-gray-200 dark:bg-gray-600 rounded-lg w-1/3 mb-2"></div>
                    <div class="h-10 bg-gray-200 dark:bg-gray-600 rounded-lg w-full mb-6"></div>
                    
                    <div class="h-24 bg-gray-200 dark:bg-gray-600 rounded-lg w-full mb-6"></div>
                    
                    <div class="flex space-x-4 mt-6">
                        <div class="h-10 bg-gray-200 dark:bg-gray-600 rounded-lg w-1/4"></div>
                        <div class="h-10 bg-gray-200 dark:bg-gray-600 rounded-lg w-3/4"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="product-detail" class="hidden">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
    
    <!-- Related Products -->
    <div id="related-products-section" class="mt-10 hidden">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Produk Terkait</h2>
            <a href="{{ route('toko.index') }}" class="text-amber-600 dark:text-amber-400 hover:underline text-sm font-medium flex items-center">
                Lihat Katalog Lengkap
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
        <div id="related-products" class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get product ID from URL
    const productId = parseInt("{{ $id }}");
    
    // Elements
    const breadcrumbs = document.getElementById('breadcrumbs');
    const productLoading = document.getElementById('product-loading');
    const productDetail = document.getElementById('product-detail');
    const relatedProductsSection = document.getElementById('related-products-section');
    const relatedProducts = document.getElementById('related-products');
    
    // Fetch product details
    fetchProduct(productId);
    
    // Helper function to format numbers with thousand separators
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Functions
    function fetchProduct(id) {
        fetch(`/api/shop/products/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Product not found');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data) {
                    renderProduct(data.data);
                    
                    // If the product has a category, fetch related products
                    if (data.data.kategori && data.data.kategori.id) {
                        fetchRelatedProducts(data.data.kategori.id, id);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching product:', error);
                productDetail.innerHTML = `
                    <div class="p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-white">Produk Tidak Ditemukan</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Maaf, produk yang Anda cari tidak tersedia.</p>
                        <a href="/toko" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-medium px-6 py-2 rounded-md text-sm transition">
                            Kembali ke Katalog
                        </a>
                    </div>
                `;
                productDetail.classList.remove('hidden');
                productLoading.classList.add('hidden');
            });
    }
    
    function fetchRelatedProducts(categoryId, currentProductId) {
        const params = new URLSearchParams({
            kategori_id: categoryId
        });
        
        fetch(`/api/shop/products?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    // Filter out the current product and limit to 4 products
                    const filtered = data.data
                        .filter(product => product.id !== parseInt(currentProductId))
                        .slice(0, 4);
                    
                    if (filtered.length > 0) {
                        renderRelatedProducts(filtered);
                        relatedProductsSection.classList.remove('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching related products:', error);
            });
    }
    
    function renderProduct(product) {
        // Update page title
        document.title = product.nama + ' - KPRI';
        
        // Update breadcrumbs
        const categoryName = product.kategori && product.kategori.nama ? product.kategori.nama : 'Tanpa Kategori';
        const categoryId = product.kategori && product.kategori.id ? product.kategori.id : '';
        
        breadcrumbs.innerHTML = `
            <a href="{{ route('toko.index') }}" class="hover:underline">Katalog</a>
            <span>/</span>
            <a href="{{ route('toko.index') }}?kategori_id=${categoryId}" class="hover:underline">${categoryName}</a>
            <span>/</span>
            <span class="truncate max-w-xs">${product.nama}</span>
        `;
        
        // Calculate discount percentage for display
        let discountPercentage = '';
        if (product.has_promo && product.promo.tipe_diskon === 'persen') {
            discountPercentage = product.promo.nilai_diskon;
        } else if (product.has_promo) {
            // Calculate percentage for nominal discount
            discountPercentage = Math.round((product.promo.nilai_diskon / product.harga) * 100);
        }
        
        // Render product details
        let productHtml = `
            <div class="md:flex">
                <!-- Product Image -->
                <div class="md:w-2/5 p-6">
                    <div class="relative bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                        <img src="${product.gambar || '/images/no-image.png'}" 
                            alt="${product.nama}" 
                            class="w-full h-auto object-contain rounded-lg">
                            
                        ${product.has_promo && discountPercentage ? `
                            <div class="absolute top-4 left-4 bg-red-500 text-white font-bold px-3 py-2 rounded-full">
                                ${discountPercentage}% OFF
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                <!-- Product Details -->
                <div class="md:w-3/5 p-6">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">${product.nama}</h1>
                    
                    <div class="flex items-center mb-4">
                        <span class="bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            ${categoryName}
                        </span>
                        
                        <span class="mx-2 text-gray-300 dark:text-gray-600">|</span>
                        
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Stok: ${product.stok}
                        </div>
                    </div>
                    
                    <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        ${product.has_promo ? `
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="line-through text-gray-500 dark:text-gray-400">Rp ${formatNumber(product.harga)}</span>
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                                    ${product.promo.tipe_diskon === 'persen' ? `DISKON ${product.promo.nilai_diskon}%` : 'HEMAT ' + formatNumber(product.promo.nilai_diskon)}
                                </span>
                            </div>
                            <div class="text-3xl font-bold text-red-600 dark:text-red-500 mb-2">
                                Rp ${formatNumber(product.harga_diskon)}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Promo berlaku hingga ${new Date(product.promo.tgl_end).toLocaleDateString('id-ID')}
                            </div>
                        ` : `
                            <div class="text-3xl font-bold text-gray-800 dark:text-white">
                                Rp ${formatNumber(product.harga)}
                            </div>
                        `}
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Deskripsi Produk
                        </h3>
                        <div class="text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            ${product.deskripsi ? product.deskripsi : 'Tidak ada deskripsi produk.'}
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah</label>
                            <div class="flex items-center">
                                <button id="decrement-qty" class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 w-8 h-8 flex items-center justify-center rounded-l-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </button>
                                <input type="number" id="quantity" name="quantity" min="1" value="1" class="w-16 h-8 text-center border-t border-b border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:outline-none focus:ring-1 focus:ring-amber-500">
                                <button id="increment-qty" class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 w-8 h-8 flex items-center justify-center rounded-r-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                            <a href="{{ route('toko.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm font-medium transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                Kembali
                            </a>
                            <button id="add-to-cart" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-md text-sm font-medium transition flex-1 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Tambah ke Keranjang
                            </button>
                            <a href="https://wa.me/6281234567890?text=Halo%20KPRI%20UNEJ,%20saya%20tertarik%20dengan%20produk%20${encodeURIComponent(product.nama)}%20(${window.location.href})" 
                                target="_blank" 
                                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm font-medium transition flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 1.856.001 3.598.723 4.907 2.034 1.31 1.311 2.031 3.054 2.03 4.908-.001 3.825-3.113 6.938-6.937 6.938z"/>
                                </svg>
                                Beli via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        productDetail.innerHTML = productHtml;
        productDetail.classList.remove('hidden');
        productLoading.classList.add('hidden');
        
        // Add event listeners for quantity controls
        const quantityInput = document.getElementById('quantity');
        const decrementBtn = document.getElementById('decrement-qty');
        const incrementBtn = document.getElementById('increment-qty');
        const addToCartBtn = document.getElementById('add-to-cart');
        
        // Ensure valid quantity
        quantityInput.addEventListener('change', function() {
            const value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                this.value = 1;
            }
        });
        
        // Decrement quantity
        decrementBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        // Increment quantity
        incrementBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        });
        
        // Add to cart functionality
        addToCartBtn.addEventListener('click', function() {
            const quantity = parseInt(quantityInput.value);
            if (isNaN(quantity) || quantity < 1) return;
            
            // Calculate the price to use (promo price or regular price)
            const price = product.has_promo ? product.harga_diskon : product.harga;
            
            // Create product object for cart
            const cartProduct = {
                id: product.id,
                name: product.nama,
                price: price,
                image: product.gambar,
                quantity: quantity
            };
            
            // Dispatch custom event to add to cart
            window.dispatchEvent(new CustomEvent('add-to-cart', {
                detail: cartProduct
            }));
        });
    }
    
    function renderRelatedProducts(products) {
        let html = '';
        
        products.forEach(product => {
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
        
        relatedProducts.innerHTML = html;
    }
});
</script>
@endpush 