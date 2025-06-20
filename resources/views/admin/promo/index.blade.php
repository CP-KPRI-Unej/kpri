@extends('admin.layouts.app')

@section('title', 'Manajemen Promo')

@section('styles')
<style>
    @keyframes pulse-highlight {
        0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
        70% { box-shadow: 0 0 0 5px rgba(245, 158, 11, 0); }
        100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    
    .pulse-animation {
        animation: pulse-highlight 1.5s infinite;
    }
    
    .status-badge {
        transition: all 0.3s ease;
    }
    
    .status-badge:hover {
        transform: scale(1.1);
    }

    .row-active {
        background-color: rgba(209, 250, 229, 0.2) !important;
    }

    .row-active:hover {
        background-color: rgba(209, 250, 229, 0.4) !important;
    }

    .dark .row-active {
        background-color: rgba(6, 95, 70, 0.15) !important;
    }
    
    .dark .row-active:hover {
        background-color: rgba(6, 95, 70, 0.25) !important;
    }
    
    .bulk-actions-container {
        display: none;
    }
    
    .bulk-actions-container.active {
        display: flex;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Promo Produk (<span id="promo-count">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola promo diskon untuk produk</p>
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
        <div class="relative w-full md:w-64">
            <input id="searchPromo" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Cari Promo">
            <div class="absolute left-3 top-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        
        <!-- Bulk actions -->
        <div id="bulkActionsContainer" class="bulk-actions-container items-center bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded-md mr-auto">
            <span class="text-sm mr-2"><span id="selectedCount">0</span> terpilih</span>
            <div x-data="{ open: false, posStyle: {} }">
                <button @click="open = !open; if (open) posStyle = getPopupPosition($event)" class="flex items-center text-sm bg-white dark:bg-gray-800 px-3 py-1 rounded border">
                    Aksi Massal
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak :style="posStyle" class="fixed rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        <button onclick="deleteBulkPromos()" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="bi bi-trash mr-2"></i> Hapus Terpilih
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <select id="statusFilter" class="border rounded-md p-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
                <option value="berakhir">Berakhir</option>
            </select>
            
            <a href="{{ route('admin.promo.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-orange-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Promo Baru
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
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300" id="selectAll">
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sortable" data-field="judul_promo">
                            <div class="flex items-center">
                                <span class="mr-2">Judul Promo</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell sortable" data-field="tgl_start">
                            <div class="flex items-center">
                                <span class="mr-2">Periode</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sortable" data-field="nilai_diskon">
                            <div class="flex items-center">
                                <span class="mr-2">Diskon</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sortable" data-field="status">
                            <div class="flex items-center">
                                <span class="mr-2">Status</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                            Produk
                        </th>
                        <th scope="col" class="relative px-3 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="promotionList" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Promotions will be loaded here via API -->
                    <tr id="loading-row">
                        <td colspan="7" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="inline-block animate-spin h-5 w-5 text-orange-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Menampilkan <span class="font-medium" id="items-count-current">0</span> dari <span class="font-medium" id="items-count-total">0</span> promo
                    </p>
                </div>
                <div id="pagination-controls">
                    <!-- Pagination will be added here via JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Konfirmasi Penghapusan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Apakah Anda yakin ingin menghapus promo ini? Tindakan ini tidak dapat dibatalkan.</p>
            
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

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ubah Status Promo</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Apakah Anda yakin ingin mengubah status promo ini?</p>
            <input type="hidden" id="promoId" value="">
            <input type="hidden" id="newStatus" value="">
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-sm">
                    Batal
                </button>
                <button type="button" id="confirmStatusBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm">
                    Ubah Status
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Global variables
    let promotions = [];
    let currentPage = 1;
    let totalPages = 1;
    let perPage = 10;
    let sortField = 'tgl_start';
    let sortDirection = 'desc';
    let searchTerm = '';
    let selectedStatus = '';
    let deletePromoId = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Set up axios defaults
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        
        // Set JWT token from localStorage if available
        const token = localStorage.getItem('access_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        } else {
            // If no token in localStorage, try to get it from the login process
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        
        // Initialize
        fetchPromotions();
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

    function fetchPromotions() {
        // Show loading state
        document.getElementById('loading-row').style.display = 'table-row';
        
        let url = `/api/admin/promotions?page=${currentPage}&per_page=${perPage}&sort_by=${sortField}&sort_direction=${sortDirection}`;
        
        if (searchTerm) {
            url += `&search=${encodeURIComponent(searchTerm)}`;
        }
        
        if (selectedStatus) {
            url += `&status=${selectedStatus}`;
        }
        
        axios.get(url)
            .then(response => {
                if (response.data.status === 'success') {
                    const data = response.data.data;
                    promotions = data.data;
                    currentPage = data.current_page;
                    totalPages = data.last_page;
                    renderPromotions();
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

    function renderPromotions() {
        const tbody = document.getElementById('promotionList');
        // Clear all except loading row
        const loadingRow = document.getElementById('loading-row');
        tbody.innerHTML = '';
        tbody.appendChild(loadingRow);
        
        if (promotions.length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td colspan="7" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    Tidak ada data promo
                </td>
            `;
            tbody.appendChild(tr);
            return;
        }
        
        promotions.forEach(promo => {
            const tr = document.createElement('tr');
            tr.className = `hover:bg-gray-50 dark:hover:bg-gray-700 ${promo.status === 'aktif' ? 'row-active' : ''}`;
            tr.dataset.id = promo.id_promo;
            
            // Determine status class
            let statusClass = '';
            let statusText = promo.status.charAt(0).toUpperCase() + promo.status.slice(1);
            
            if (promo.status === 'aktif') {
                statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
            } else if (promo.status === 'nonaktif') {
                statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
            } else {
                statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
            }
            
            // Format dates
            const startDate = new Date(promo.tgl_start);
            const endDate = new Date(promo.tgl_end);
            const formattedStart = startDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            const formattedEnd = endDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            
            tr.innerHTML = `
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                        <input type="checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300 promo-checkbox" data-id="${promo.id_promo}">
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex flex-col sm:flex-row sm:items-center">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">${promo.judul_promo.length > 20 ? promo.judul_promo.substring(0, 20) + '...' : promo.judul_promo}</div>
                            </div>
                        </td>
                        <td class="px-3 py-4 hidden md:table-cell">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                        ${formattedStart} - ${formattedEnd}
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                        ${promo.tipe_diskon === 'persen' 
                            ? `<span class="font-bold text-orange-500">${promo.nilai_diskon}%</span>` 
                            : `<span class="font-bold text-orange-500">Rp ${new Intl.NumberFormat('id-ID').format(promo.nilai_diskon)}</span>`
                        }
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                        ${statusText}
                            </span>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                            <div class="flex items-center">
                                <div class="mr-2">
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-orange-600 rounded-md shadow-sm status-badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                ${promo.products ? promo.products.length : 0}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="relative" x-data="{ open: false, posStyle: {} }">
                                <button @click="open = !open; if (open) posStyle = getPopupPosition($event)" class="text-gray-400 hover:text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak :style="posStyle" class="fixed rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-40">
                                    <div class="py-1" role="menu" aria-orientation="vertical">
                                ${promo.status !== 'berakhir' ? `
                                    ${promo.status !== 'aktif' ? `
                                        <button onclick="showStatusModal(${promo.id_promo}, 'aktif')" class="w-full text-left block px-4 py-2 text-sm text-green-700 dark:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                        <i class="bi bi-check-circle mr-2"></i> Aktifkan
                                                    </button>
                                    ` : ''}
                                    ${promo.status !== 'nonaktif' ? `
                                        <button onclick="showStatusModal(${promo.id_promo}, 'nonaktif')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                        <i class="bi bi-pause-circle mr-2"></i> Non-aktifkan
                                                    </button>
                                    ` : ''}
                                ` : ''}
                                <a href="/admin/promo/edit/${promo.id_promo}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-pencil mr-2"></i> Edit
                                        </a>
                                <button onclick="showDeleteModal(${promo.id_promo})" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <i class="bi bi-trash mr-2"></i> Hapus
                                            </button>
                                    </div>
                                </div>
                            </div>
                        </td>
            `;
            tbody.appendChild(tr);
        });
        
        // Update the selected count after rendering
        updateSelectedCount();
    }

    function updatePagination(data) {
        const paginationControls = document.getElementById('pagination-controls');
        paginationControls.innerHTML = '';
        
        // Only show pagination if we have more than one page
        if (data.last_page <= 1) return;
        
        // Create pagination nav element
        const nav = document.createElement('nav');
        nav.className = 'relative z-0 inline-flex rounded-md shadow-sm -space-x-px';
        nav.setAttribute('aria-label', 'Pagination');
        
        // Previous button
        const prevButton = document.createElement('button');
        prevButton.className = 'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600' + (data.current_page === 1 ? ' opacity-50 cursor-not-allowed' : '');
        prevButton.disabled = data.current_page === 1;
        prevButton.innerHTML = `
            <span class="sr-only">Previous</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        `;
        prevButton.onclick = () => {
            if (data.current_page > 1) {
                currentPage = data.current_page - 1;
                fetchPromotions();
            }
        };
        nav.appendChild(prevButton);
        
        // Page numbers
        const pageNumbersContainer = document.createElement('div');
        pageNumbersContainer.id = 'pageNumbers';
        pageNumbersContainer.className = 'flex';
        
        // Logic to show appropriate page numbers
        let startPage = Math.max(1, data.current_page - 2);
        let endPage = Math.min(data.last_page, startPage + 4);
        
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.className = i === data.current_page
                ? 'relative inline-flex items-center px-4 py-2 border border-orange-500 bg-orange-50 dark:bg-orange-900 text-sm font-medium text-orange-600 dark:text-orange-200'
                : 'relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600';
            pageButton.innerHTML = i;
            pageButton.onclick = () => {
                currentPage = i;
                fetchPromotions();
            };
            pageNumbersContainer.appendChild(pageButton);
        }
        nav.appendChild(pageNumbersContainer);
        
        // Next button
        const nextButton = document.createElement('button');
        nextButton.className = 'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600' + (data.current_page === data.last_page ? ' opacity-50 cursor-not-allowed' : '');
        nextButton.disabled = data.current_page === data.last_page;
        nextButton.innerHTML = `
            <span class="sr-only">Next</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        `;
        nextButton.onclick = () => {
            if (data.current_page < data.last_page) {
                currentPage = data.current_page + 1;
                fetchPromotions();
            }
        };
        nav.appendChild(nextButton);
        
        paginationControls.appendChild(nav);
    }

    function updateItemCount(total) {
        document.getElementById('promo-count').textContent = total;
        document.getElementById('items-count-total').textContent = total;
        document.getElementById('items-count-current').textContent = promotions.length;
    }

    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('.promo-checkbox:checked');
        const count = selectedCheckboxes.length;
        
        // Update select all checkbox state
        const selectAllCheckbox = document.getElementById('selectAll');
        const allCheckboxes = document.querySelectorAll('.promo-checkbox');
        selectAllCheckbox.checked = allCheckboxes.length > 0 && allCheckboxes.length === selectedCheckboxes.length;
        
        // Update bulk actions container visibility
        document.getElementById('selectedCount').textContent = count;
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        if (count > 0) {
            bulkActionsContainer.classList.add('active');
        } else {
            bulkActionsContainer.classList.remove('active');
        }
    }

    function setupEventListeners() {
        // Search functionality
        const searchInput = document.getElementById('searchPromo');
        searchInput.addEventListener('input', debounce(function(e) {
            searchTerm = e.target.value.trim();
            currentPage = 1; // Reset to first page on search
            fetchPromotions();
        }, 500));
        
        // Status filter
        const statusFilter = document.getElementById('statusFilter');
        statusFilter.addEventListener('change', function(e) {
            selectedStatus = e.target.value;
            currentPage = 1; // Reset to first page on filter change
            fetchPromotions();
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
                
                // Fetch promotions with new sort
                currentPage = 1; // Reset to first page on sort change
                fetchPromotions();
            });
        });
        
        // Select all checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.promo-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedCount();
        });
        
        // Event delegation for checkbox changes in the promo list
        document.getElementById('promotionList').addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('promo-checkbox')) {
                updateSelectedCount();
            }
        });
        
        // Confirm delete button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deletePromoId) {
                deletePromotion(deletePromoId);
                closeDeleteModal();
            }
        });
                
        // Confirm status change button
        document.getElementById('confirmStatusBtn').addEventListener('click', function() {
            const promoId = document.getElementById('promoId').value;
            const newStatus = document.getElementById('newStatus').value;
            if (promoId && newStatus) {
                updatePromotionStatus(promoId, newStatus);
                closeStatusModal();
            }
        });
    }

    function updateSortIcons(field, direction) {
        // Reset all icons first
        document.querySelectorAll('.sort-icon').forEach(icon => {
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            `;
            icon.parentElement.parentElement.classList.remove('text-orange-600');
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
            header.classList.add('text-orange-600');
        }
    }

    function deletePromotion(id) {
        axios.delete(`/api/admin/promotions/${id}`)
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', response.data.message || 'Promo berhasil dihapus');
                    fetchPromotions(); // Refresh the list
                }
            })
            .catch(error => {
                showAlert('error', 'Gagal menghapus promo: ' + (error.response?.data?.message || error.message));
            });
    }

    function updatePromotionStatus(id, status) {
        axios.patch(`/api/admin/promotions/${id}/status`, { status: status })
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', response.data.message || 'Status promo berhasil diubah');
                    fetchPromotions(); // Refresh the list
                }
            })
            .catch(error => {
                showAlert('error', 'Gagal mengubah status: ' + (error.response?.data?.message || error.message));
            });
    }

    function showDeleteModal(id) {
        deletePromoId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        deletePromoId = null;
    }

    function showStatusModal(id, status) {
        document.getElementById('promoId').value = id;
        document.getElementById('newStatus').value = status;
        document.getElementById('statusModal').classList.remove('hidden');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
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
    
    // Function to get popup position for bulk actions
    function getPopupPosition(event) {
        const button = event.currentTarget;
        const rect = button.getBoundingClientRect();
        const popupWidth = 192; // w-48 = 12rem = 192px
        
        // Calculate position to ensure the popup is fully visible
        let leftPos = rect.left;
        
        // Check if popup would go off the right edge of the screen
        if (leftPos + popupWidth > window.innerWidth - 10) {
            leftPos = window.innerWidth - popupWidth - 10; // 10px margin from right edge
        }
        
        return {
            position: 'fixed',
            top: `${rect.bottom + 5}px`, // 5px offset from button
            left: `${leftPos}px`,
            width: `${popupWidth}px`,
            zIndex: 50
        };
    }
    
    // Function to handle bulk delete
    function deleteBulkPromos() {
        const selectedCheckboxes = document.querySelectorAll('.promo-checkbox:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);
        
        if (selectedIds.length === 0) {
            showAlert('error', 'Tidak ada promo yang dipilih');
            return;
        }
        
        if (confirm(`Apakah Anda yakin ingin menghapus ${selectedIds.length} promo yang dipilih?`)) {
            // Use Promise.all for parallel requests
            const deletePromises = selectedIds.map(id => 
                axios.delete(`/api/admin/promotions/${id}`)
                .then(response => {
                    if (!response.data || response.data.status !== 'success') {
                        throw new Error(`Failed to delete promo ${id}`);
                    }
                    return response.data;
                })
            );
            
            Promise.all(deletePromises)
                .then(() => {
                    // Refresh the promo list
                    fetchPromotions();
                    showAlert('success', `${selectedIds.length} promo berhasil dihapus`);
                })
                .catch(error => {
                    console.error('Error deleting promos:', error);
                    showAlert('error', 'Gagal menghapus beberapa promo. Silakan coba lagi.');
                    // Refresh anyway to show the current state
                    fetchPromotions();
                });
        }
    }
</script>
@endpush

@endsection 