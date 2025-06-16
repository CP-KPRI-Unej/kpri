@extends('admin.layouts.app')

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
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Kategori Produk (<span id="categoryCount">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan atau edit kategori produk dari web</p>
        </div>

    <div id="success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="success-message">Operation successful</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.classList.add('hidden')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
                    </button>
                </div>

    <div id="error-alert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="error-message">An error occurred</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.classList.add('hidden')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
                    </button>
                </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="relative w-full md:w-64">
            <input id="searchKategori" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Cari Kategori">
            <div class="absolute left-3 top-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <a href="{{ route('admin.kategori.create') }}" class="bg-indigo-800 text-white px-4 py-2 rounded-md text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Kategori Baru
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div id="loading-state" class="p-6 flex justify-center items-center">
            <div class="loading-spinner text-blue-500"></div>
            <span class="ml-3 text-gray-600 dark:text-gray-400">Memuat data...</span>
        </div>
        
        <div id="content-state" class="hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300">
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nama Kategori
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jumlah Produk
                        </th>
                        <th scope="col" class="relative px-3 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                        </tr>
                    </thead>
                    <tbody id="categoryList" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
            <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                <div class="sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-400">
                            Showing <span class="font-medium" id="categoryCountBottom">0</span> categories
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
                <a href="{{ route('admin.kategori.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Kategori
                </a>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div id="modalOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Hapus Kategori
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Apakah Anda yakin ingin menghapus kategori "<span id="deleteItemName" class="font-semibold text-gray-700 dark:text-gray-300"></span>"? Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmDelete" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                    <button type="button" id="cancelDelete" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
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
        
        // Modal elements
        const deleteModal = document.getElementById('deleteModal');
        const deleteItemName = document.getElementById('deleteItemName');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        const cancelDeleteBtn = document.getElementById('cancelDelete');
        const modalOverlay = document.getElementById('modalOverlay');
        let categoryToDelete = null;
        
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
        modalOverlay.addEventListener('click', closeDeleteModal);
        confirmDeleteBtn.addEventListener('click', deleteCategory);
        
        // Add click event listener for delete buttons using event delegation
        categoryList.addEventListener('click', function(e) {
            const deleteButton = e.target.closest('.delete-category');
            if (deleteButton && !deleteButton.disabled) {
                const categoryId = deleteButton.dataset.id;
                const categoryName = deleteButton.dataset.name;
                const hasProducts = deleteButton.dataset.hasProducts === 'true';
                
                if (hasProducts) {
                    showError('Tidak dapat menghapus kategori yang memiliki produk');
                    return;
                }
                
                openDeleteModal(categoryId, categoryName);
            }
        });
        
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
                    showError(data.message || 'Failed to fetch categories');
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
                showError('An unexpected error occurred while fetching categories');
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
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300">
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
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-400 hover:text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-40">
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
                    showSuccess('Kategori berhasil dihapus');
                    // Refresh the category list
                    fetchCategories();
                } else {
                    showError(data.message || 'Gagal menghapus kategori');
                    closeDeleteModal();
                }
            } catch (error) {
                console.error('Error deleting category:', error);
                showError('Terjadi kesalahan saat menghapus kategori');
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
        
        // Show success message
        function showSuccess(message) {
            const successAlert = document.getElementById('success-alert');
            const successMessage = document.getElementById('success-message');
            
            successMessage.textContent = message;
            successAlert.classList.remove('hidden');
            
            // Hide after 3 seconds
            setTimeout(() => {
                successAlert.classList.add('hidden');
            }, 3000);
        }
        
        // Show error message
        function showError(message) {
            const errorAlert = document.getElementById('error-alert');
            const errorMessage = document.getElementById('error-message');
            
            errorMessage.textContent = message;
            errorAlert.classList.remove('hidden');
            
            // Hide after 5 seconds
            setTimeout(() => {
                errorAlert.classList.add('hidden');
            }, 5000);
        }
    });
</script>
@endpush

@endsection 