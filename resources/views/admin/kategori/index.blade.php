@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori')

@section('styles')
<style>
    .row-has-products {
        background-color: rgba(254, 243, 199, 0.2) !important;
    }

    .row-has-products:hover {
        background-color: rgba(254, 243, 199, 0.4) !important;
    }

    .dark .row-has-products {
        background-color: rgba(120, 53, 15, 0.15) !important;
    }
    
    .dark .row-has-products:hover {
        background-color: rgba(120, 53, 15, 0.25) !important;
    }
    
    .loading-spinner {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        vertical-align: text-bottom;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border .75s linear infinite;
    }
    
    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
    
    .input-stroke {
        border: 2px solid #e5e7eb;
        transition: border-color 0.2s ease;
    }
    .input-stroke:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2);
    }
    .dark .input-stroke {
        border-color: #4b5563;
    }
    .dark .input-stroke:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.3);
    }
    
    .bulk-actions-container {
        display: none;
    }
    
    .bulk-actions-container.flex {
        display: flex;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Kategori Produk (<span id="categoryCount">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola kategori produk yang tersedia untuk pengguna</p>
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
            <input id="searchKategori" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 input-stroke" placeholder="Cari Kategori">
            <div class="absolute left-3 top-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        
        <!-- Bulk actions -->
        <div id="bulkActionsContainer" class="bulk-actions-container items-center bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded-md mr-auto hidden">
            <span class="text-sm mr-2"><span id="selectedCount">0</span> terpilih</span>
            <div x-data="{ open: false, posStyle: {} }" id="bulkActionsDropdown">
                <button @click="open = !open; if (open) posStyle = getPopupPosition($event)" class="flex items-center text-sm bg-white dark:bg-gray-800 px-3 py-1 rounded border">
                    Aksi Massal
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak :style="posStyle" class="fixed rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        <button onclick="bulkDeleteCategories()" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="bi bi-trash mr-2"></i> Hapus Terpilih
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <a href="{{ route('admin.kategori.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-orange-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Kategori Baru
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div id="loading-state" class="p-6 flex justify-center items-center">
            <svg class="inline-block animate-spin h-5 w-5 text-orange-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-3 text-gray-600 dark:text-gray-400">Memuat data...</span>
        </div>
        
        <div id="content-state" class="hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" id="select-all-checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300">
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nama Kategori
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jumlah Produk
                        </th>
                        <th scope="col" class="relative px-3 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                        </tr>
                    </thead>
                    <tbody id="categoryList" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
            <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-400">
                            Menampilkan <span class="font-medium" id="categoryCountBottom">0</span> kategori
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Empty state -->
        <div id="empty-state" class="p-6 text-center hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada data kategori</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan kategori baru.</p>
            <div class="mt-6">
                <a href="{{ route('admin.kategori.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Kategori
                </a>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Konfirmasi Penghapusan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Apakah Anda yakin ingin menghapus kategori "<span id="deleteItemName" class="font-semibold text-gray-700 dark:text-gray-300"></span>"? Tindakan ini tidak dapat dibatalkan.</p>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelDelete" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-sm">
                        Batal
                    </button>
                    <button type="button" id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get authentication token
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/admin/login';
            return;
        }
        
        // DOM elements
        const searchInput = document.getElementById('searchKategori');
        const loadingState = document.getElementById('loading-state');
        const contentState = document.getElementById('content-state');
        const emptyState = document.getElementById('empty-state');
        const categoryList = document.getElementById('categoryList');
        const categoryCount = document.getElementById('categoryCount');
        const categoryCountBottom = document.getElementById('categoryCountBottom');
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const selectedCountEl = document.getElementById('selectedCount');
        
        // Modal elements
        const deleteModal = document.getElementById('deleteModal');
        const deleteItemName = document.getElementById('deleteItemName');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        const cancelDeleteBtn = document.getElementById('cancelDelete');
        let categoryToDelete = null;
        
        // Alpine.js helper function for positioning dropdowns
        window.getPopupPosition = function(event) {
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
        };
        
        // Load categories
        fetchCategories();
        
        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                filterCategories(this.value);
            });
        }
        
        // Setup delete modal event listeners
        cancelDeleteBtn.addEventListener('click', closeDeleteModal);
        confirmDeleteBtn.addEventListener('click', deleteCategory);
        
        // Add click event listener for delete buttons using event delegation
        categoryList.addEventListener('click', function(e) {
            const deleteButton = e.target.closest('.delete-category');
            if (deleteButton && !deleteButton.disabled) {
                const categoryId = deleteButton.dataset.id;
                const categoryName = deleteButton.dataset.name;
                const hasProducts = deleteButton.dataset.hasProducts === 'true';
                
                if (hasProducts) {
                    showAlert('Tidak dapat menghapus kategori yang memiliki produk', 'error');
                    return;
                }
                
                openDeleteModal(categoryId, categoryName);
            }
        });
        
        // Setup select all checkbox
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                    if (!checkbox.disabled) {
                        checkbox.checked = isChecked;
                    }
                });
                updateBulkActionsVisibility();
            });
        }
        
        // Fetch categories from API
        async function fetchCategories() {
            try {
                showLoading();
                
                const response = await fetch('/api/admin/categories', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch categories');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    const categories = data.data;
                    
                    if (categories.length === 0) {
                        showEmptyState();
                    } else {
                        renderCategories(categories);
                        showContentState();
                    }
                } else {
                    showAlert(data.message || 'Failed to fetch categories', 'error');
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
                showAlert('An unexpected error occurred while fetching categories', 'error');
            }
        }
        
        // Render categories to the DOM
        function renderCategories(categories) {
            // Clear existing content
            categoryList.innerHTML = '';
            
            // Update count
            categoryCount.textContent = categories.length;
            categoryCountBottom.textContent = categories.length;
            
            // Add categories to the list
            categories.forEach(category => {
                const row = document.createElement('tr');
                row.className = `hover:bg-gray-50 dark:hover:bg-gray-700 ${category.produks_count > 0 ? 'row-has-products' : ''}`;
                
                row.innerHTML = `
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                    class="category-checkbox form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300" 
                                    data-id="${category.id_kategori}" 
                                    data-has-products="${category.produks_count > 0}" 
                                    ${category.produks_count > 0 ? 'disabled' : ''}
                                    onchange="updateBulkActionsVisibility()">
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex flex-col sm:flex-row sm:items-center">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${category.kategori}</div>
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-md shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                    </svg>
                                ${category.produks_count}
                                </span>
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
                                    <a href="${window.location.origin}/admin/kategori/${category.id_kategori}/edit" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-pencil mr-2"></i> Edit
                                        </a>
                                    <button type="button" class="delete-category w-full text-left block px-4 py-2 text-sm ${category.produks_count > 0 ? 'text-gray-400 dark:text-gray-500 cursor-not-allowed' : 'text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700'}" 
                                        role="menuitem" 
                                        data-id="${category.id_kategori}" 
                                        data-name="${category.kategori}"
                                        data-has-products="${category.produks_count > 0}"
                                        ${category.produks_count > 0 ? 'disabled' : ''}>
                                                <i class="bi bi-trash mr-2"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                                </td>
                `;
                
                categoryList.appendChild(row);
            });
        }
        
        // Open delete confirmation modal
        function openDeleteModal(categoryId, categoryName) {
            categoryToDelete = categoryId;
            deleteItemName.textContent = categoryName;
            deleteModal.classList.remove('hidden');
        }
        
        // Close delete confirmation modal
        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            categoryToDelete = null;
        }
        
        // Delete category
        async function deleteCategory() {
            if (!categoryToDelete) return;
            
            try {
                // Show loading on button
                confirmDeleteBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menghapus...
                `;
                confirmDeleteBtn.disabled = true;
                
                const response = await fetch(`/api/admin/categories/${categoryToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    closeDeleteModal();
                    showAlert('Kategori berhasil dihapus', 'success');
                    // Refresh the category list
                    fetchCategories();
                } else {
                    showAlert(data.message || 'Gagal menghapus kategori', 'error');
                    closeDeleteModal();
                }
            } catch (error) {
                console.error('Error deleting category:', error);
                showAlert('Terjadi kesalahan saat menghapus kategori', 'error');
                closeDeleteModal();
            }
        }
        
        // Filter categories based on search query
        function filterCategories(query) {
            const rows = document.querySelectorAll('#categoryList tr');
            const searchValue = query.toLowerCase();
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const isVisible = text.includes(searchValue);
                row.style.display = isVisible ? '' : 'none';
                
                if (isVisible) {
                    visibleCount++;
                }
            });
            
            // Update visible count
            categoryCountBottom.textContent = visibleCount;
        }
        
        // Show loading state
        function showLoading() {
            loadingState.classList.remove('hidden');
            contentState.classList.add('hidden');
            emptyState.classList.add('hidden');
        }
        
        // Show content state
        function showContentState() {
            loadingState.classList.add('hidden');
            contentState.classList.remove('hidden');
            emptyState.classList.add('hidden');
        }
        
        // Show empty state
        function showEmptyState() {
            loadingState.classList.add('hidden');
            contentState.classList.add('hidden');
            emptyState.classList.remove('hidden');
        }
        
        // Show alert message
        function showAlert(message, type = 'success') {
            const alertId = type === 'success' ? 'alert-success' : 'alert-error';
            const alertEl = document.getElementById(alertId);
            const messageEl = type === 'success' ? document.getElementById('success-message') : document.getElementById('error-message');
            
            if (alertEl && messageEl) {
                messageEl.textContent = message;
                alertEl.classList.remove('hidden');
                
                setTimeout(() => {
                    alertEl.classList.add('hidden');
                }, type === 'success' ? 3000 : 5000);
            }
        }
        
        // Hide alert
        window.hideAlert = function(alertId) {
            const alertEl = document.getElementById(alertId);
            if (alertEl) {
                alertEl.classList.add('hidden');
            }
        }
        
        // Make updateBulkActionsVisibility available globally
        window.updateBulkActionsVisibility = function() {
            const selectedCheckboxes = document.querySelectorAll('.category-checkbox:checked');
            const selectedCount = selectedCheckboxes.length;
            
            selectedCountEl.textContent = selectedCount;
            
            if (selectedCount > 0) {
                bulkActionsContainer.classList.remove('hidden');
                bulkActionsContainer.classList.add('flex');
            } else {
                bulkActionsContainer.classList.add('hidden');
                bulkActionsContainer.classList.remove('flex');
            }
        }
        
        // Bulk delete categories
        window.bulkDeleteCategories = function() {
            const selectedCheckboxes = document.querySelectorAll('.category-checkbox:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);
            
            if (selectedIds.length === 0) {
                showAlert('Tidak ada kategori yang dipilih', 'error');
                return;
            }
            
            if (!confirm(`Apakah Anda yakin ingin menghapus ${selectedIds.length} kategori terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
                return;
            }
            
            // Close dropdown if it's open
            const dropdownComponent = document.getElementById('bulkActionsDropdown').__x;
            if (dropdownComponent && dropdownComponent.$data.open) {
                dropdownComponent.$data.open = false;
            }
            
            // Show loading state
            const bulkDeleteBtn = document.querySelector('button[onclick="bulkDeleteCategories()"]');
            const originalBtnText = bulkDeleteBtn.innerHTML;
            bulkDeleteBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menghapus...
            `;
            bulkDeleteBtn.disabled = true;
            
            // Create promises for each delete request
            const deletePromises = selectedIds.map(id => 
                fetch(`/api/admin/categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
            );
            
            // Execute all delete requests
            Promise.all(deletePromises)
                .then(results => {
                    const successCount = results.filter(result => result.success).length;
                    const errorCount = results.length - successCount;
                    
                    if (successCount > 0) {
                        showAlert(`Berhasil menghapus ${successCount} kategori${errorCount > 0 ? `, ${errorCount} gagal dihapus` : ''}`, 'success');
                        
                        // Reset the select all checkbox
                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = false;
                        }
                        
                        // Reset bulk actions container
                        bulkActionsContainer.classList.add('hidden');
                        bulkActionsContainer.classList.remove('flex');
                        
                        // Refresh the list
                        fetchCategories();
                    } else {
                        showAlert('Gagal menghapus kategori', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error bulk deleting categories:', error);
                    showAlert('Terjadi kesalahan saat menghapus kategori', 'error');
                })
                .finally(() => {
                    // Reset button state
                    bulkDeleteBtn.innerHTML = originalBtnText;
                    bulkDeleteBtn.disabled = false;
                });
        }
    });
</script>
@endpush

@endsection 