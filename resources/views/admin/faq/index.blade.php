@extends('admin.layouts.app')

@section('title', 'Manajemen FAQ')

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
        <h1 class="text-2xl font-semibold">Frequently Asked Questions (<span id="faqCount">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola daftar pertanyaan dan jawaban yang sering ditanyakan</p>
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
            <input id="searchFAQ" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Cari FAQ">
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
                        <button onclick="deleteBulkFAQs()" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="bi bi-trash mr-2"></i> Hapus Terpilih
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <a href="{{ route('admin.faq.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-orange-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah FAQ
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
                            Pertanyaan
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">
                            Jawaban
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                            Oleh
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="relative px-3 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="faqList" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr id="loading-indicator">
                        <td colspan="6" class="px-3 py-4 text-center">
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
                        Menampilkan <span class="font-medium" id="faqCountFooter">0</span> FAQ
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
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Apakah Anda yakin ingin menghapus FAQ ini? Tindakan ini tidak dapat dibatalkan.</p>
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
            fetchFAQData();
        } else {
            // If no token in localStorage, try to get it from the login process
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        
        // Set up search functionality
        const searchInput = document.getElementById('searchFAQ');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                filterFAQs();
            });
        }
        
        // Select all checkbox functionality
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('#faqList input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });
        }
        
        // Event delegation for checkbox changes in the table body
        document.getElementById('faqList').addEventListener('change', function(e) {
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
                    fetchFAQData();
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Fetch FAQ data from API
    function fetchFAQData() {
        const loadingIndicator = document.getElementById('loading-indicator');
        loadingIndicator.style.display = 'table-row';
        
        axios.get('/api/admin/faqs')
            .then(response => {
                if (response.data.status === 'success') {
                    renderFAQList(response.data.data);
                } else {
                    showAlert('error', 'Failed to load FAQ data');
                }
                loadingIndicator.style.display = 'none';
            })
            .catch(error => {
                console.error('Error fetching FAQ data:', error);
                showAlert('error', 'Error loading FAQ data: ' + (error.response?.data?.message || error.message));
                loadingIndicator.style.display = 'none';
                
                // Check if unauthorized and redirect to login
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Filter FAQs based on search input
    function filterFAQs() {
        const searchValue = document.getElementById('searchFAQ').value.toLowerCase();
        const tableRows = document.querySelectorAll('#faqList tr:not(#loading-indicator)');
        
        // Filter table rows
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    }
    
    // Update selected count
    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('#faqList input[type="checkbox"]:checked');
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
    function deleteBulkFAQs() {
        const selectedCheckboxes = document.querySelectorAll('#faqList input[type="checkbox"]:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
        
        if (selectedIds.length === 0) {
            alert('Tidak ada FAQ yang dipilih');
            return;
        }
        
        if (confirm(`Apakah Anda yakin ingin menghapus ${selectedIds.length} FAQ yang dipilih?`)) {
            // Use Promise.all for parallel requests
            const deletePromises = selectedIds.map(id => 
                axios.delete(`/api/admin/faqs/${id}`)
                .then(response => {
                    if (!response.data || response.data.status !== 'success') {
                        throw new Error(`Failed to delete item ${id}`);
                    }
                    return response.data;
                })
            );
            
            Promise.all(deletePromises)
                .then(() => {
                    // Refresh the FAQ list
                    fetchFAQData();
                    showAlert('success', `${selectedIds.length} FAQ berhasil dihapus`);
                })
                .catch(error => {
                    console.error('Error deleting items:', error);
                    showAlert('error', 'Gagal menghapus beberapa FAQ. Silakan coba lagi.');
                    // Refresh anyway to show the current state
                    fetchFAQData();
                });
        }
    }
    
    // Render FAQ list
    function renderFAQList(data) {
        const faqList = document.getElementById('faqList');
        const loadingIndicator = document.getElementById('loading-indicator');
        
        // Update FAQ count
        document.getElementById('faqCount').textContent = data.length;
        document.getElementById('faqCountFooter').textContent = data.length;
        
        // Clear previous content except loading indicator
        Array.from(faqList.children).forEach(child => {
            if (child.id !== 'loading-indicator') {
                faqList.removeChild(child);
            }
        });
        
        // Check if no data
        if (data.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `
                <td colspan="6" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    Tidak ada FAQ yang tersedia
                </td>
            `;
            faqList.appendChild(emptyRow);
            return;
        }
        
        // Add FAQ items
        data.forEach(faq => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';
            
            // Truncate description for display
            const truncatedDescription = faq.deskripsi.length > 100 
                ? faq.deskripsi.substring(0, 100) + '...' 
                : faq.deskripsi;
            
            // Determine status badge color
            let statusBadgeClass = '';
            let statusText = faq.status ? faq.status.nama_status : 'Default';
            
            // Use different colors for different statuses - simplify to match gallery section
            const statusLower = statusText.toLowerCase();
            statusBadgeClass = (statusLower === 'aktif' || statusLower === 'active' || statusLower === 'published')
                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
            
            row.innerHTML = `
                <td class="px-3 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <input type="checkbox" class="faq-checkbox form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300" value="${faq.id_faq}">
                    </div>
                </td>
                <td class="px-3 py-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${faq.judul}</div>
                </td>
                <td class="px-3 py-4 hidden md:table-cell">
                    <div class="text-sm text-gray-500 dark:text-gray-400">${truncatedDescription}</div>
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                    ${faq.user ? faq.user.nama_user : 'Tidak diketahui'}
                </td>
                <td class="px-3 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusBadgeClass}">
                        ${statusText}
                    </span>
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
                                <a href="/admin/faq/${faq.id_faq}/edit" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                    <i class="bi bi-pencil mr-2"></i> Edit
                                </a>
                                <button onclick="showDeleteModal(${faq.id_faq})" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                    <i class="bi bi-trash mr-2"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </td>
            `;
            
            faqList.appendChild(row);
        });
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
        
        axios.delete(`/api/admin/faqs/${id}`)
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', 'FAQ berhasil dihapus');
                    fetchFAQData();
                    closeDeleteModal();
                } else {
                    showAlert('error', 'Gagal menghapus FAQ');
                    closeDeleteModal();
                }
            })
            .catch(error => {
                console.error('Error deleting FAQ:', error);
                showAlert('error', 'Error deleting FAQ: ' + (error.response?.data?.message || error.message));
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