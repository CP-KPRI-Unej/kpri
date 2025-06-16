@extends('admin.layouts.app')

@section('title', 'Manajemen Download Item')

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
    .file-icon {
        font-size: 1.5rem;
    }
    .pdf-icon { color: #dc3545; }
    .doc-icon { color: #0d6efd; }
    .xls-icon { color: #198754; }
    .ppt-icon { color: #fd7e14; }
    .zip-icon { color: #6c757d; }
    .default-icon { color: #6c757d; }
    
    @keyframes pulse-highlight {
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
        70% { box-shadow: 0 0 0 5px rgba(79, 70, 229, 0); }
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }
    
    .save-order-button {
        animation: pulse-highlight 1.5s infinite;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Download Item (<span id="item-count">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola item download yang tersedia untuk pengguna</p>
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
            <input id="searchDownload" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Cari Item Download">
            <div class="absolute left-3 top-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <div class="relative">
                <button id="saveOrderBtn" style="display: none;" class="flex items-center px-3 py-2 border rounded-md text-sm bg-green-600 text-white save-order-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Urutan
                </button>
            </div>
            
            <a href="{{ route('admin.download.create') }}" class="bg-indigo-800 text-white px-4 py-2 rounded-md text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Item Download Baru
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
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-500 rounded border-gray-300">
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">#</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nama Item
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">
                            File
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                            Tanggal Upload
                        </th>
                        <th scope="col" class="relative px-3 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="sortableList" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Items will be loaded here via API -->
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
            <div class="sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Showing <span class="font-medium" id="items-count-footer">0</span> items
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
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.</p>
            
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    let downloadItems = [];
    let sortable;
    let orderChanged = false;
    let deleteItemId = null;

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
        fetchDownloadItems();
        setupEventListeners();
        initializeSortable();
    });

    // Check authentication status
    function checkAuthentication() {
        axios.get('/api/auth/me')
            .then(response => {
                // Store the token for future requests
                const token = response.data.access_token;
                if (token) {
                    localStorage.setItem('jwt_token', token);
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

    function fetchDownloadItems() {
        axios.get('/api/admin/downloads')
            .then(response => {
                if (response.data.status === 'success') {
                    downloadItems = response.data.data;
                    renderDownloadItems();
                    updateItemCount();
                }
            })
            .catch(error => {
                showAlert('error', 'Gagal memuat data: ' + (error.response?.data?.message || error.message));
            });
    }

    function renderDownloadItems() {
        const tbody = document.getElementById('sortableList');
        // Clear loading row
        tbody.innerHTML = '';
        
        if (downloadItems.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        Tidak ada data item download
                    </td>
                </tr>
            `;
            return;
        }
        
        downloadItems.forEach((item, index) => {
            // Determine file extension and icon
            const extension = getFileExtension(item.path_file);
            let iconClass = 'default-icon';
            let icon = 'file-earmark';
            
            if (['pdf'].includes(extension)) {
                iconClass = 'pdf-icon';
                icon = 'file-earmark-pdf';
            } else if (['doc', 'docx'].includes(extension)) {
                iconClass = 'doc-icon';
                icon = 'file-earmark-word';
            } else if (['xls', 'xlsx'].includes(extension)) {
                iconClass = 'xls-icon';
                icon = 'file-earmark-excel';
            } else if (['ppt', 'pptx'].includes(extension)) {
                iconClass = 'ppt-icon';
                icon = 'file-earmark-ppt';
            } else if (['zip', 'rar'].includes(extension)) {
                iconClass = 'zip-icon';
                icon = 'file-earmark-zip';
            }
            
            // Determine status class
            const statusClass = item.status === 'Active' 
                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
            
            // Format date
            const uploadDate = new Date(item.tgl_upload);
            const formattedDate = `${uploadDate.getDate().toString().padStart(2, '0')}/${(uploadDate.getMonth() + 1).toString().padStart(2, '0')}/${uploadDate.getFullYear()}`;
            
            const tr = document.createElement('tr');
            tr.className = 'sortable-item hover:bg-gray-50 dark:hover:bg-gray-700';
            tr.dataset.id = item.id_download_item;
            console.log('Setting item ID:', item.id_download_item);
            tr.innerHTML = `
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-500 rounded border-gray-300">
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2 sort-handle transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                        <span class="sort-order">${index + 1}</span>
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${item.nama_item}</div>
                        </td>
                        <td class="px-3 py-4 hidden md:table-cell">
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <i class="bi bi-${icon} ${iconClass} mr-2"></i>
                        <a href="/storage/${item.path_file}" target="_blank" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                            ${getFileName(item.path_file)}
                                </a>
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                        ${item.status}
                            </span>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                    ${formattedDate}
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
                                <a href="/storage/${item.path_file}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-download mr-2"></i> Download
                                        </a>
                                <a href="/admin/download/${item.id_download_item}/edit" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-pencil mr-2"></i> Edit
                                        </a>
                                <button onclick="showDeleteModal(${item.id_download_item})" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
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

    function initializeSortable() {
        const el = document.getElementById('sortableList');
        sortable = new Sortable(el, {
                animation: 150,
                handle: '.sort-handle',
                ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-drag',
            onEnd: function() {
                updateOrderNumbers();
                        orderChanged = true;
                        document.getElementById('saveOrderBtn').style.display = 'flex';
                }
            });
        }
        
    function updateOrderNumbers() {
            const items = document.querySelectorAll('#sortableList .sortable-item');
            items.forEach((item, index) => {
                item.querySelector('.sort-order').textContent = index + 1;
            });
        }
        
        function saveOrder() {
            const items = document.querySelectorAll('#sortableList .sortable-item');
        const data = {
            items: []
        };
            
        items.forEach((item, index) => {
            data.items.push({
                id: parseInt(item.dataset.id),
                urutan: index + 1
            });
            });
            
        // Show loading state
        const saveButton = document.getElementById('saveOrderBtn');
        const originalText = saveButton.innerHTML;
        saveButton.disabled = true;
        saveButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
        
        // Call the API with correct headers
        axios.post('/api/admin/downloads/update-order', data)
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', response.data.message);
                    orderChanged = false;
                    saveButton.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Update order error:', error);
                let errorMessage = 'Gagal menyimpan urutan';
                
                if (error.response) {
                    // The request was made and the server responded with an error status
                    errorMessage += ': ' + (error.response.data.message || error.message);
                    
                    // If we have validation errors, show them
                    if (error.response.data.errors) {
                        console.error('Validation errors:', error.response.data.errors);
                    }
                } else if (error.request) {
                    // The request was made but no response was received
                    errorMessage += ': Tidak ada respons dari server';
                } else {
                    // Something happened in setting up the request
                    errorMessage += ': ' + error.message;
                }
                
                showAlert('error', errorMessage);
            })
            .finally(() => {
                // Restore button state
                saveButton.disabled = false;
                saveButton.innerHTML = originalText;
            });
    }

    function deleteItem(id) {
        axios.delete(`/api/admin/downloads/${id}`)
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', response.data.message);
                    fetchDownloadItems();
        }
            })
            .catch(error => {
                showAlert('error', 'Gagal menghapus item: ' + (error.response?.data?.message || error.message));
            });
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

    function updateItemCount() {
        document.getElementById('item-count').textContent = downloadItems.length;
        document.getElementById('items-count-footer').textContent = downloadItems.length;
    }

    function getFileExtension(filename) {
        return filename.split('.').pop().toLowerCase();
        }
        
    function getFileName(path) {
        return path.split('/').pop();
    }

    function setupEventListeners() {
        // Save order button
        document.getElementById('saveOrderBtn').addEventListener('click', saveOrder);
        
        // Search functionality
        document.getElementById('searchDownload').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#sortableList .sortable-item');
            
            rows.forEach(row => {
                const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const file = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || file.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
            }
        });
    });
    }

    function showDeleteModal(id) {
        deleteItemId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
        
        // Set up confirmation button
        document.getElementById('confirmDeleteBtn').onclick = function() {
            deleteItem(deleteItemId);
            closeDeleteModal();
        };
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteItemId = null;
    }
</script>
@endpush