@extends('admin.layouts.app')

@section('title', 'Manajemen Hero Banner')

@section('styles')
<style>
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
        <h1 class="text-2xl font-semibold">Hero Banner (<span id="bannerCount">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola banner utama yang ditampilkan pada halaman beranda</p>
    </div>

    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="success-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-success')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="error-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-error')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="relative w-full md:w-64">
            <input id="searchBanner" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Cari Banner">
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
                        <button onclick="deleteBulkBanners()" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="bi bi-trash mr-2"></i> Hapus Terpilih
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">            
            <a href="{{ route('admin.hero-banners.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-orange-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Banner
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
                                <input type="checkbox" id="selectAllCheckbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300">
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Judul
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">
                            Deskripsi
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                            URL
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Gambar
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">
                            Ditambahkan Oleh
                        </th>
                        <th scope="col" class="relative px-3 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="bannerTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr id="loading-row">
                        <td colspan="7" class="px-3 py-4 text-center">
                            <svg class="animate-spin h-5 w-5 mx-auto text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            <span class="text-sm text-gray-500 mt-2 block">Memuat data...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Menampilkan <span class="font-medium" id="bannerCountFooter">0</span> banner
                    </p>
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
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Apakah Anda yakin ingin menghapus banner ini? Tindakan ini tidak dapat dibatalkan.</p>
            <input type="hidden" id="delete-id" value="">
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-sm">
                    Batal
                </button>
                <button type="button" onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Fungsi untuk menghitung posisi popup - didefinisikan di luar DOMContentLoaded agar bisa diakses secara global
    function getPopupPosition(event) {
        const button = event.currentTarget;
        const rect = button.getBoundingClientRect();
        const popupWidth = 192; // w-48 = 12rem = 192px
        
        // Pastikan popup tidak keluar dari batas kanan layar
        let leftPos = rect.right - popupWidth;
        if (leftPos < 10) leftPos = 10; // Beri sedikit margin jika terlalu ke kiri
        
        return {
            position: 'fixed',
            top: `${rect.bottom + 5}px`, // 5px offset dari tombol
            left: `${leftPos}px`,
            width: `${popupWidth}px`
        };
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Set up axios defaults
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        
        // Set JWT token from localStorage if available
        const token = localStorage.getItem('access_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            fetchBanners();
        } else {
            // If no token in localStorage, try to get it from the login process
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        
        // Set up search functionality
        const searchInput = document.getElementById('searchBanner');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('#bannerTableBody tr:not(#loading-row)');
                
                // Filter table rows
                tableRows.forEach(row => {
                    if (row.hasAttribute('data-banner-title')) {
                        const title = row.getAttribute('data-banner-title').toLowerCase();
                        const desc = row.getAttribute('data-banner-desc').toLowerCase();
                        row.style.display = title.includes(searchValue) || desc.includes(searchValue) ? '' : 'none';
                    }
                });
            });
        }
        
        // Select all checkbox functionality
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('#bannerTableBody input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });
        }
        
        // Event delegation for checkbox changes in the table body
        document.getElementById('bannerTableBody').addEventListener('change', function(e) {
            if (e.target && e.target.type === 'checkbox') {
                updateSelectedCount();
            }
        });
    });
    
    // Check authentication status
    function checkAuthentication() {
        axios.get('/api/auth/me')
            .then(response => {
                const token = response.data.access_token;
                if (token) {
                    localStorage.setItem('access_token', token);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    fetchBanners();
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Fetch banners from API
    function fetchBanners() {
        const loadingRow = document.getElementById('loading-row');
        if (loadingRow) loadingRow.style.display = 'table-row';
        
        axios.get('/api/admin/hero-banners')
            .then(response => {
                if (response.data.status === 'success') {
                    renderBannerList(response.data.data);
                } else {
                    showAlert('error', 'Failed to load banner data');
                }
                if (loadingRow) loadingRow.style.display = 'none';
            })
            .catch(error => {
                console.error('Error fetching banners:', error);
                showAlert('error', 'Error loading banner data: ' + (error.response?.data?.message || error.message));
                if (loadingRow) loadingRow.style.display = 'none';
                
                // Check if unauthorized and redirect to login
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Render banner list
    function renderBannerList(banners) {
        const tableBody = document.getElementById('bannerTableBody');
        
        // Update banner count
        document.getElementById('bannerCount').textContent = banners.length;
        document.getElementById('bannerCountFooter').textContent = banners.length;
        
        // Clear previous content except loading row
        Array.from(tableBody.children).forEach(child => {
            if (child.id !== 'loading-row') {
                tableBody.removeChild(child);
            }
        });
        
        // Check if no data
        if (banners.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `
                <td colspan="7" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    Tidak ada banner yang tersedia
                </td>
            `;
            tableBody.appendChild(emptyRow);
            return;
        }
        
        // Add banner items
        banners.forEach(banner => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';
            row.setAttribute('data-banner-title', banner.judul);
            row.setAttribute('data-banner-desc', banner.deskripsi);
            
            // Truncate description for display
            const truncatedDescription = banner.deskripsi.length > 100 
                ? banner.deskripsi.substring(0, 100) + '...' 
                : banner.deskripsi;
                
            // Truncate URL for display
            const truncatedUrl = banner.url.length > 30
                ? banner.url.substring(0, 30) + '...'
                : banner.url;
            
            row.innerHTML = `
                <td class="px-3 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <input type="checkbox" class="banner-checkbox form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300" value="${banner.id_hero}">
                    </div>
                </td>
                <td class="px-3 py-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${banner.judul}</div>
                </td>
                <td class="px-3 py-4 hidden md:table-cell">
                    <div class="text-sm text-gray-500 dark:text-gray-400">${truncatedDescription}</div>
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                    <a href="${banner.url}" target="_blank" class="text-blue-500 hover:underline">${truncatedUrl}</a>
                </td>
                <td class="px-3 py-4 whitespace-nowrap">
                    <img src="/storage/${banner.gambar}" class="h-10 w-auto rounded shadow" alt="${banner.judul}" onclick="window.open('/storage/${banner.gambar}', '_blank')">
                </td>
                <td class="px-3 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${banner.status?.nama_status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                        ${banner.status ? banner.status.nama_status : 'Tidak diketahui'}
                    </span>
                </td>
                <td class="px-3 py-4 whitespace-nowrap hidden md:table-cell">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        ${banner.user ? banner.user.nama_user : 'Tidak diketahui'}
                    </div>
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="relative" x-data="{ open: false, posStyle: {} }">
                        <button @click="open = !open; if (open) posStyle = getActionPopupPosition($event)" class="text-gray-400 hover:text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak :style="posStyle" class="fixed rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-40">
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                <a href="/admin/hero-banners/${banner.id_hero}/edit" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                    <i class="bi bi-pencil mr-2"></i> Edit
                                </a>
                                <button onclick="showDeleteModal(${banner.id_hero})" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                    <i class="bi bi-trash mr-2"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Reset selected count after rendering
        updateSelectedCount();
    }
    
    // Update selected count
    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('#bannerTableBody input[type="checkbox"]:checked');
        const count = selectedCheckboxes.length;
        document.getElementById('selectedCount').textContent = count;
        
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        if (count > 0) {
            bulkActionsContainer.classList.add('active');
        } else {
            bulkActionsContainer.classList.remove('active');
            // Uncheck the select all checkbox if no items are selected
            document.getElementById('selectAllCheckbox').checked = false;
        }
    }
    
    // Function to handle bulk delete
    function deleteBulkBanners() {
        const selectedCheckboxes = document.querySelectorAll('#bannerTableBody input[type="checkbox"]:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
        
        if (selectedIds.length === 0) {
            alert('Tidak ada banner yang dipilih');
            return;
        }
        
        if (confirm(`Apakah Anda yakin ingin menghapus ${selectedIds.length} banner yang dipilih?`)) {
            // Use Promise.all for parallel requests
            const deletePromises = selectedIds.map(id => 
                axios.delete(`/api/admin/hero-banners/${id}`)
                .then(response => {
                    if (!response.data || response.data.status !== 'success') {
                        throw new Error(`Failed to delete item ${id}`);
                    }
                    return response.data;
                })
            );
            
            Promise.all(deletePromises)
                .then(() => {
                    // Refresh the banner list
                    fetchBanners();
                    showAlert('success', `${selectedIds.length} banner berhasil dihapus`);
                })
                .catch(error => {
                    console.error('Error deleting items:', error);
                    showAlert('error', 'Gagal menghapus beberapa banner. Silakan coba lagi.');
                    // Refresh anyway to show the current state
                    fetchBanners();
                });
        }
    }
    
    // Show delete confirmation modal
    function showDeleteModal(id) {
        document.getElementById('delete-id').value = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    
    // Close delete confirmation modal
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    // Confirm delete action
    function confirmDelete() {
        const id = document.getElementById('delete-id').value;
        
        axios.delete(`/api/admin/hero-banners/${id}`)
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', 'Banner berhasil dihapus');
                    fetchBanners();
                    closeDeleteModal();
                } else {
                    showAlert('error', 'Gagal menghapus banner');
                    closeDeleteModal();
                }
            })
            .catch(error => {
                console.error('Error deleting banner:', error);
                showAlert('error', 'Error deleting banner: ' + (error.response?.data?.message || error.message));
                closeDeleteModal();
            });
    }
    
    // Function to get action popup position
    function getActionPopupPosition(event) {
        const button = event.currentTarget;
        const rect = button.getBoundingClientRect();
        const popupWidth = 192; // Width of the popup (w-48 = 12rem = 192px)
        const windowWidth = window.innerWidth;
        
        // Default to placing the popup to the left of the button
        let leftPos = rect.right - popupWidth;
        
        // If this would place the popup off the left edge, position it differently
        if (leftPos < 10) {
            leftPos = 10; // Minimum 10px from left edge
        }
        
        // If the popup would go off the right edge, position it to the left of the button
        if (rect.left + popupWidth > windowWidth - 10) {
            leftPos = Math.max(10, windowWidth - popupWidth - 10);
        }
        
        return {
            position: 'fixed',
            top: `${rect.bottom + 5}px`, // 5px offset from button
            left: `${leftPos}px`,
            width: `${popupWidth}px`,
            zIndex: '50'
        };
    }
    
    // Show alert messages
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
    
    // Hide alert messages
    function hideAlert(elementId) {
        document.getElementById(elementId).classList.add('hidden');
    }
</script>
@endpush

@endsection 
 
 
 
 
 
 
 
 
 
 
 