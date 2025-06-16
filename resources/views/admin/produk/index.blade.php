@extends('admin.layouts.app')

@section('title', 'Manajemen Produk')

@section('styles')
<style>
    .sortable-item {
        cursor: grab;
    }
    .sortable-item.sortable-ghost {
        opacity: 0.4;
        background-color: #e5edff !important;
    }
    .dark .sortable-item.sortable-ghost {
        background-color: #283548 !important;
    }
    .sortable-item.sortable-drag {
        opacity: 0.8;
        background-color: #f3f4f6;
    }
    .dark .sortable-item.sortable-drag {
        background-color: #374151;
    }
    .sort-handle {
        cursor: grab;
    }
    .sort-handle:hover {
        color: #4f46e5;
    }
    .product-image {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
    }
    @keyframes pulse-highlight {
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
        70% { box-shadow: 0 0 0 5px rgba(79, 70, 229, 0); }
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Produk (<span id="item-count">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola produk yang tersedia untuk pengguna</p>
    </div>

    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="success-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-success')" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="error-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-error')" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
        <div class="relative w-full md:w-64">
                <input id="searchProduct" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Cari Produk">
            <div class="absolute left-3 top-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            </div>
            
            <select id="categoryFilter" class="border rounded-md p-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full md:w-auto">
                <option value="">Semua Kategori</option>
                <!-- Categories will be loaded here via API -->
            </select>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <a href="{{ route('admin.produk.create') }}" class="bg-indigo-800 text-white px-4 py-2 rounded-md text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Produk Baru
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-500 rounded border-gray-300" id="selectAll">
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Gambar
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sortable" data-field="nama_produk">
                            <div class="flex items-center">
                                <span class="mr-2">Nama Produk</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sortable" data-field="harga_produk">
                            <div class="flex items-center">
                                <span class="mr-2">Harga</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sortable" data-field="stok_produk">
                            <div class="flex items-center">
                                <span class="mr-2">Stok</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="relative px-3 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                    <tbody id="productList" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Products will be loaded here via API -->
                    <tr id="loading-row">
                        <td colspan="7" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="inline-block animate-spin h-5 w-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
            <div class="flex items-center">
                        <p class="text-sm text-gray-700 dark:text-gray-400">
                    Showing <span class="font-medium" id="items-count-current">0</span> of <span class="font-medium" id="items-count-total">0</span> products
                        </p>
            </div>
            <div class="flex justify-between gap-2" id="pagination-controls">
                <!-- Pagination will be added here via JS -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Konfirmasi Penghapusan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.</p>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-sm">
                    Batal
                </button>
                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Global variables
    let products = [];
    let categories = [];
    let currentPage = 1;
    let totalPages = 1;
    let perPage = 15;
    let sortField = 'nama_produk';
    let sortDirection = 'asc';
    let searchTerm = '';
    let selectedCategory = '';
    let deleteProductId = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Set up axios defaults
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        
        // Set JWT token from localStorage if available
        const token = localStorage.getItem('access_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        } else {
            // If no token in localStorage, try to get it from the login process or redirect to login
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        
        // Initialize
        fetchCategories();
        fetchProducts();
        setupEventListeners();
    });

    // Check authentication status
    function checkAuthentication() {
        axios.get('/api/auth/me')
            .then(response => {
                // Store the token for future requests
                const token = response.data.access_token;
                if (token) {
                    localStorage.setItem('access_token', token);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                // Redirect to login if unauthenticated
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }

    function fetchCategories() {
        axios.get('/api/admin/product-categories')
            .then(response => {
                if (response.data.status === 'success') {
                    categories = response.data.data;
                    populateCategoryDropdown();
        }
            })
            .catch(error => {
                showAlert('error', 'Gagal memuat kategori: ' + (error.response?.data?.message || error.message));
            });
    }

    function populateCategoryDropdown() {
        const categoryFilter = document.getElementById('categoryFilter');
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id_kategori;
            option.textContent = category.kategori;
            categoryFilter.appendChild(option);
        });
    }

    function fetchProducts() {
        // Show loading state
        document.getElementById('loading-row').style.display = 'table-row';
        
        let url = `/api/admin/products?page=${currentPage}&per_page=${perPage}&sort_by=${sortField}&sort_direction=${sortDirection}`;
        
        if (searchTerm) {
            url += `&search=${encodeURIComponent(searchTerm)}`;
        }
        
        if (selectedCategory) {
            url += `&category=${selectedCategory}`;
        }
        
        axios.get(url)
            .then(response => {
                if (response.data.status === 'success') {
                    const data = response.data.data;
                    products = data.data;
                    currentPage = data.current_page;
                    totalPages = data.last_page;
                    renderProducts();
                    updatePagination(data);
                    updateItemCount(data.total);
                }
                // Hide loading row
                document.getElementById('loading-row').style.display = 'none';
            })
            .catch(error => {
                showAlert('error', 'Gagal memuat data: ' + (error.response?.data?.message || error.message));
                // Hide loading row
                document.getElementById('loading-row').style.display = 'none';
            });
    }

    function renderProducts() {
        const tbody = document.getElementById('productList');
        // Clear all except loading row
        const loadingRow = document.getElementById('loading-row');
        tbody.innerHTML = '';
        tbody.appendChild(loadingRow);
                    
                    if (products.length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td colspan="7" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    Tidak ada data produk
                </td>
            `;
            tbody.appendChild(tr);
            return;
        }
        
            products.forEach(product => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';
            tr.dataset.id = product.id_produk;
            
            // Format price
            const formattedPrice = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(product.harga_produk);
            
            // Get category name
            const categoryName = product.category ? product.category.kategori : 'Tidak ada kategori';
            
            // Image path
            const imagePath = product.gambar_produk 
                ? `/storage/${product.gambar_produk}` 
                : '/images/no-image.png';
            
            tr.innerHTML = `
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                        <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-500 rounded border-gray-300 product-checkbox" data-id="${product.id_produk}">
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                    <div class="flex-shrink-0">
                        <img src="${imagePath}" alt="${product.nama_produk}" class="product-image">
                            </div>
                        </td>
                <td class="px-3 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${product.nama_produk}</div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        ${categoryName}
                            </span>
                        </td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${formattedPrice}
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ${product.stok_produk}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-400 hover:text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-40">
                                    <div class="py-1" role="menu" aria-orientation="vertical">
                                <a href="/admin/produk/${product.id_produk}/edit" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-pencil mr-2"></i> Edit
                                        </a>
                                <button onclick="showDeleteModal(${product.id_produk})" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <i class="bi bi-trash mr-2"></i> Hapus
                                            </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                `;
            tbody.appendChild(tr);
            });
    }

    function updatePagination(data) {
        const paginationControls = document.getElementById('pagination-controls');
        paginationControls.innerHTML = '';
        
        // Only show pagination if we have more than one page
        if (data.last_page <= 1) return;
        
        // Previous button
        const prevButton = document.createElement('button');
        prevButton.className = 'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600';
        prevButton.innerHTML = 'Previous';
        prevButton.disabled = data.current_page === 1;
        prevButton.onclick = () => {
            if (data.current_page > 1) {
                currentPage = data.current_page - 1;
                fetchProducts();
            }
        };
        paginationControls.appendChild(prevButton);
        
        // Page numbers
        const pagesContainer = document.createElement('div');
        pagesContainer.className = 'hidden md:flex gap-1';
        
        // Logic to show appropriate page numbers
        let startPage = Math.max(1, data.current_page - 2);
        let endPage = Math.min(data.last_page, startPage + 4);
                
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
                }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.className = i === data.current_page
                ? 'relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md bg-indigo-600 text-white'
                : 'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600';
            pageButton.innerHTML = i;
            pageButton.onclick = () => {
                currentPage = i;
                fetchProducts();
            };
            pagesContainer.appendChild(pageButton);
        }
        paginationControls.appendChild(pagesContainer);
            
        // Next button
        const nextButton = document.createElement('button');
        nextButton.className = 'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600';
        nextButton.innerHTML = 'Next';
        nextButton.disabled = data.current_page === data.last_page;
        nextButton.onclick = () => {
            if (data.current_page < data.last_page) {
                currentPage = data.current_page + 1;
                fetchProducts();
        }
        };
        paginationControls.appendChild(nextButton);
    }

    function updateItemCount(total) {
        document.getElementById('item-count').textContent = total;
        document.getElementById('items-count-total').textContent = total;
        document.getElementById('items-count-current').textContent = products.length;
    }

    function setupEventListeners() {
        // Search functionality
        const searchInput = document.getElementById('searchProduct');
        searchInput.addEventListener('input', debounce(function(e) {
            searchTerm = e.target.value.trim();
            currentPage = 1; // Reset to first page on search
            fetchProducts();
        }, 500));
        
        // Category filter
        const categoryFilter = document.getElementById('categoryFilter');
        categoryFilter.addEventListener('change', function(e) {
            selectedCategory = e.target.value;
            currentPage = 1; // Reset to first page on filter change
            fetchProducts();
        });
        
        // Sorting
        const sortableHeaders = document.querySelectorAll('.sortable');
        sortableHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const field = this.getAttribute('data-field');
                
                // Toggle direction if clicking on the same field
                if (field === sortField) {
                    sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    sortField = field;
                    sortDirection = 'asc';
                }
                
                // Update sort icons
                updateSortIcons(field, sortDirection);
                    
                // Fetch products with new sort
                currentPage = 1; // Reset to first page on sort change
                    fetchProducts();
            });
        });
        
        // Select all checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
        
        // Confirm delete button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteProductId) {
                deleteProduct(deleteProductId);
                closeDeleteModal();
            }
        });
    }

    function updateSortIcons(field, direction) {
        // Reset all icons first
        document.querySelectorAll('.sort-icon').forEach(icon => {
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            `;
            icon.parentElement.parentElement.classList.remove('text-indigo-600');
        });
        
        // Update the clicked header's icon
        const header = document.querySelector(`.sortable[data-field="${field}"]`);
        if (header) {
            const icon = header.querySelector('.sort-icon');
            if (direction === 'asc') {
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                `;
            } else {
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                `;
            }
            header.classList.add('text-indigo-600');
        }
    }

    function deleteProduct(id) {
        axios.delete(`/api/admin/products/${id}`)
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', response.data.message);
                    fetchProducts(); // Refresh the list
                }
            })
            .catch(error => {
                showAlert('error', 'Gagal menghapus produk: ' + (error.response?.data?.message || error.message));
            });
    }

    function showDeleteModal(id) {
        deleteProductId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteProductId = null;
        }
        
    function showAlert(type, message) {
        const alertElement = document.getElementById(`alert-${type}`);
        const messageElement = document.getElementById(`${type}-message`);
            
        messageElement.textContent = message;
        alertElement.classList.remove('hidden');
            
        // Auto hide after 5 seconds
            setTimeout(() => {
            hideAlert(`alert-${type}`);
            }, 5000);
        }

    function hideAlert(elementId) {
        document.getElementById(elementId).classList.add('hidden');
    }

    // Utility function to debounce inputs
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
</script>
@endpush