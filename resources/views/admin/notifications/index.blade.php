@extends('admin.layouts.app')

@section('title', 'Manajemen Notifikasi')

@section('styles')
<style>
    .status-sent {
        background-color: #dcfce7;
        color: #166534;
    }
    .dark .status-sent {
        background-color: #14532d;
        color: #dcfce7;
    }
    .status-scheduled {
        background-color: #ffedd5;
        color: #9a3412;
    }
    .dark .status-scheduled {
        background-color: #7c2d12;
        color: #ffedd5;
    }
    .bulk-actions-container {
        display: none;
    }
    
    .bulk-actions-container.active {
        display: flex;
    }
    
    /* Input field styles with stroke */
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
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Notifikasi (<span id="notificationCount">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola notifikasi push untuk user.</p>
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
        <div class="flex items-center gap-2">
            <div class="relative w-full md:w-64">
                <input id="search-input" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 input-stroke" placeholder="Cari notifikasi...">
                <div class="absolute left-3 top-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex rounded-md shadow-sm" role="group">
              <button type="button" data-status="" class="status-filter-btn px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-orange-500 dark:focus:text-white ring-2 ring-orange-500">
                Semua
              </button>
              <button type="button" data-status="sent" class="status-filter-btn px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-orange-500 dark:focus:text-white">
                Terkirim
              </button>
              <button type="button" data-status="scheduled" class="status-filter-btn px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-md hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-orange-500 dark:focus:text-white">
                Terjadwal
              </button>
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
                        <button id="bulk-send-btn" class="w-full text-left block px-4 py-2 text-sm text-green-700 dark:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="bi bi-send mr-2"></i> Kirim Terpilih
                        </button>
                        <button id="bulk-delete-btn" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="bi bi-trash mr-2"></i> Hapus Terpilih
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <a href="{{ route('admin.notification.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-orange-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Notifikasi Baru
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
                                <input type="checkbox" id="select-all-checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">Pesan</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">Tanggal Terjadwal</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">Dibuat Oleh</th>
                        <th scope="col" class="relative px-3 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody id="notifications-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Loading state -->
                    <tr id="loading-row">
                        <td colspan="7" class="px-3 py-4 text-center">
                            <svg class="inline-block animate-spin h-5 w-5 text-orange-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memuat...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="pagination-controls" class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Menampilkan <span class="font-medium" id="items-count-footer">0</span> item
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
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Apakah Anda yakin ingin menghapus notifikasi ini? Tindakan ini tidak dapat dibatalkan.</p>
            
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('access_token');
    if (!token) {
        window.location.href = '/admin/login';
        return;
    }

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

    const API_URL = '/api/admin/notifications';
    let currentPage = 1;
    let currentSearch = '';
    let currentStatus = '';
    let debounceTimer;

    const searchInput = document.getElementById('search-input');
    const tableBody = document.getElementById('notifications-table-body');
    const notificationCountEl = document.getElementById('notificationCount');
    const paginationControls = document.getElementById('pagination-controls');
    const filterButtons = document.querySelectorAll('.status-filter-btn');
    const itemsCountFooter = document.getElementById('items-count-footer');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountEl = document.getElementById('selectedCount');
    

    // Check for success message in URL
    const urlParams = new URLSearchParams(window.location.search);
    const successMessage = urlParams.get('success');
    if (successMessage) {
        showAlert(decodeURIComponent(successMessage), 'success');
        // Remove the success parameter from URL without refreshing
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, '', url);
    }

    function fetchNotifications() {
        let url = `${API_URL}?page=${currentPage}&per_page=10`;
        
        if (currentSearch) {
            url += `&search=${encodeURIComponent(currentSearch)}`;
        }
        
        if (currentStatus) {
            url += `&status=${currentStatus}`;
        }
        
        showLoadingState();

        fetch(url, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    localStorage.removeItem('access_token');
                    window.location.href = '/admin/login';
                }
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                renderTable(data.data.data || []);
                renderPagination(data.data);
                notificationCountEl.textContent = data.data.total || 0;
                itemsCountFooter.textContent = data.data.total || 0;
            } else {
                throw new Error(data.message || 'Failed to fetch notifications');
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
            showErrorState(error.message);
        });
    }

    function renderTable(notifications) {
        tableBody.innerHTML = '';
        if (!notifications || notifications.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="7" class="px-3 py-4 text-center text-sm text-gray-500">Tidak ada data notifikasi</td></tr>`;
            return;
        }

        notifications.forEach(notification => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';

            const statusBadge = notification.is_sent 
                ? `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-sent">Terkirim</span>`
                : `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-scheduled">Terjadwal</span>`;
            
            const title = notification.title || 'No Title';
            const message = notification.message || 'No Message';
            
            row.innerHTML = `
                <td class="px-3 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <input type="checkbox" class="notification-checkbox form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500" data-id="${notification.id}" data-is-sent="${notification.is_sent ? '1' : '0'}">
                    </div>
                </td>
                <td class="px-3 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        ${notification.icon ? `<img src="${notification.icon}" alt="Icon" class="h-8 w-8 mr-2 rounded-full object-cover">` : ''}
                        <div class="text-sm font-medium text-gray-900 dark:text-white">${escapeHtml(title)}</div>
                    </div>
                </td>
                <td class="px-3 py-4 hidden md:table-cell">
                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">${escapeHtml(message)}</div>
                </td>
                <td class="px-3 py-4 whitespace-nowrap">${statusBadge}</td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                    ${notification.scheduled_at ? formatDate(notification.scheduled_at) : 'N/A'}
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                    ${notification.user ? notification.user.nama_user : 'N/A'}
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="relative" x-data="{ open: false, posStyle: {} }">
                        <button @click="open = !open; if (open) posStyle = getPopupPosition($event)" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak :style="posStyle" class="fixed rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-40">
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                ${notification.is_sent 
                                    ? `<a href="/admin/notification/${notification.id}/edit?reschedule=true" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem"><i class="bi bi-calendar mr-2"></i> Jadwalkan Ulang</a>` 
                                    : `<a href="/admin/notification/${notification.id}/edit" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem"><i class="bi bi-pencil mr-2"></i> Edit</a>
                                       <button onclick="sendNow(${notification.id})" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem"><i class="bi bi-send mr-2"></i> Kirim Sekarang</button>`}
                                <button onclick="deleteNotification(${notification.id})" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem"><i class="bi bi-trash mr-2"></i> Hapus</button>
                            </div>
                        </div>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);
        });

        // Add event listeners for checkboxes
        setupCheckboxes();
    }

    function renderPagination(paginationData) {
        paginationControls.innerHTML = '';
        
        if (!paginationData || !paginationData.total || paginationData.total <= 0) {
            return;
        }
        
        if (paginationData.total <= paginationData.per_page) {
            paginationControls.innerHTML = `
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div><p class="text-sm text-gray-700 dark:text-gray-400">Menampilkan <span class="font-medium">${paginationData.total}</span> hasil</p></div>
                </div>
            `;
            return;
        }

        let links = '';
        if (paginationData.links && Array.isArray(paginationData.links)) {
            paginationData.links.forEach(link => {
                if (link.url) {
                    const pageNum = link.url.split('page=')[1];
                    links += `<button data-page="${pageNum}" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium ${link.active ? 'z-10 bg-orange-50 border-orange-500 text-orange-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'}">${link.label.replace('&laquo;', '«').replace('&raquo;', '»')}</button>`;
                } else {
                    links += `<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-400">${link.label.replace('&laquo;', '«').replace('&raquo;', '»')}</span>`;
                }
            });
        }

        paginationControls.innerHTML = `
            <div class="flex-1 flex justify-between sm:hidden">
                <button data-page="${paginationData.current_page - 1}" ${!paginationData.prev_page_url ? 'disabled' : ''} class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 ${!paginationData.prev_page_url ? 'opacity-50 cursor-not-allowed' : ''}">Sebelumnya</button>
                <button data-page="${paginationData.current_page + 1}" ${!paginationData.next_page_url ? 'disabled' : ''} class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 ${!paginationData.next_page_url ? 'opacity-50 cursor-not-allowed' : ''}">Selanjutnya</button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div><p class="text-sm text-gray-700 dark:text-gray-400">Menampilkan <span class="font-medium">${paginationData.from || 0}</span> sampai <span class="font-medium">${paginationData.to || 0}</span> dari <span class="font-medium">${paginationData.total}</span> hasil</p></div>
                <div><nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">${links}</nav></div>
            </div>
        `;
    }
    
    function showLoadingState() {
        tableBody.innerHTML = `<tr><td colspan="7" class="px-3 py-4 text-center"><div class="animate-pulse flex justify-center items-center"><svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="ml-2">Memuat notifikasi...</span></div></td></tr>`;
    }

    function showErrorState(message = 'Gagal memuat notifikasi. Silakan coba lagi nanti.') {
        tableBody.innerHTML = `<tr><td colspan="7" class="px-3 py-4 text-center text-sm text-red-500">${message}</td></tr>`;
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return 'Invalid Date';
            return date.toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
        } catch (e) {
            console.error('Date formatting error:', e);
            return dateString;
        }
    }
    
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    
    function showAlert(message, type = 'success') {
        const alertId = type === 'success' ? 'alert-success' : 'alert-error';
        const alertEl = document.getElementById(alertId);
        const messageEl = alertId === 'alert-success' ? document.getElementById('success-message') : document.getElementById('error-message');
        
        if (alertEl && messageEl) {
            messageEl.textContent = message;
            alertEl.classList.remove('hidden');
            
            setTimeout(() => {
                alertEl.classList.add('hidden');
            }, 5000);
        }
    }

    function hideAlert(alertId) {
        const alertEl = document.getElementById(alertId);
        if (alertEl) {
            alertEl.classList.add('hidden');
        }
    }

    // Event Listeners
    searchInput.addEventListener('keyup', (e) => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            currentSearch = e.target.value;
            currentPage = 1;
            fetchNotifications();
        }, 500);
    });
    
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('ring-2', 'ring-orange-500'));
            button.classList.add('ring-2', 'ring-orange-500');
            currentStatus = button.dataset.status;
            currentPage = 1;
            fetchNotifications();
        });
    });

    paginationControls.addEventListener('click', (e) => {
        const target = e.target.closest('button[data-page]');
        if (target && !target.disabled) {
            currentPage = target.dataset.page;
            fetchNotifications();
            // Scroll to top of table
            const tableTop = document.querySelector('table').getBoundingClientRect().top + window.pageYOffset - 20;
            window.scrollTo({ top: tableTop, behavior: 'smooth' });
        }
    });
    
   
    // Global functions for actions
    window.deleteNotification = function(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus notifikasi ini? Tindakan ini tidak dapat dibatalkan.')) return;

        fetch(`${API_URL}/${id}`, {
            method: 'DELETE',
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
             if (response.status === 401) {
                localStorage.removeItem('access_token');
                window.location.href = '/admin/login';
                return;
            }
            if (!response.ok) {
                 return response.json().then(err => { throw new Error(err.message || 'Gagal menghapus notifikasi') });
            }
            return response.json();
        })
        .then(data => {
            showAlert(data.message || 'Notifikasi berhasil dihapus.');
            fetchNotifications();
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
            showAlert(error.message || 'Gagal menghapus notifikasi.', 'error');
        });
    };
    
    window.sendNow = function(id) {
        if (!confirm('Apakah Anda yakin ingin mengirim notifikasi ini sekarang?')) return;

        fetch(`${API_URL}/${id}/send-now`, {
            method: 'POST',
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401) {
                localStorage.removeItem('access_token');
                window.location.href = '/admin/login';
                return;
            }
            if (!response.ok) {
                 return response.json().then(err => { throw new Error(err.message || 'Gagal mengirim notifikasi') });
            }
            return response.json();
        })
        .then(data => {
            showAlert(data.message || 'Notifikasi berhasil dikirim.');
            fetchNotifications();
        })
        .catch(error => {
            console.error('Error sending notification:', error);
            showAlert(error.message || 'Gagal mengirim notifikasi.', 'error');
        });
    };

    window.rescheduleNotification = function(id) {
        window.location.href = `/admin/notification/${id}/edit?reschedule=true`;
    };

    // Setup checkboxes and bulk actions
    function setupCheckboxes() {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const notificationCheckboxes = document.querySelectorAll('.notification-checkbox');
        const bulkSendBtn = document.getElementById('bulk-send-btn');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        
        // Select all checkbox
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            notificationCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            toggleBulkActions();
        });
        
        // Individual checkboxes
        notificationCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                toggleBulkActions();
                
                // Update "select all" checkbox
                const allChecked = document.querySelectorAll('.notification-checkbox:checked').length === notificationCheckboxes.length;
                selectAllCheckbox.checked = allChecked;
            });
        });
        
        // Bulk send button
        bulkSendBtn.addEventListener('click', function() {
            const selectedIds = getSelectedNotificationIds(false); // Only get unsent notifications
            if (selectedIds.length === 0) {
                showAlert('Tidak ada notifikasi terjadwal yang dipilih', 'error');
                return;
            }
            
            if (!confirm(`Apakah Anda yakin ingin mengirim ${selectedIds.length} notifikasi sekarang?`)) return;
            
            bulkSend(selectedIds);
        });
        
        // Bulk delete button
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedIds = getSelectedNotificationIds();
            if (selectedIds.length === 0) {
                showAlert('No notifications selected', 'error');
                return;
            }
            
            if (!confirm(`Apakah Anda yakin ingin menghapus ${selectedIds.length} notifikasi?`)) return;
            
            bulkDelete(selectedIds);
        });
    }
    
    function toggleBulkActions() {
        const selectedCount = document.querySelectorAll('.notification-checkbox:checked').length;
        selectedCountEl.textContent = selectedCount;
        
        if (selectedCount > 0) {
            bulkActionsContainer.classList.add('active');
        } else {
            bulkActionsContainer.classList.remove('active');
        }
    }
    
    function getSelectedNotificationIds(includeAll = true) {
        const selectedIds = [];
        document.querySelectorAll('.notification-checkbox:checked').forEach(checkbox => {
            // If includeAll is false, only include unsent notifications
            if (includeAll || checkbox.dataset.isSent === '0') {
                selectedIds.push(parseInt(checkbox.dataset.id));
            }
        });
        return selectedIds;
    }
    
    function bulkSend(ids) {
        const originalBtnText = document.getElementById('bulk-send-btn').innerHTML;
        document.getElementById('bulk-send-btn').innerHTML = `<svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...`;
        document.getElementById('bulk-send-btn').disabled = true;
        
        const promises = ids.map(id => 
            fetch(`${API_URL}/${id}/send-now`, {
                method: 'POST',
                headers: { 
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(`Gagal mengirim notifikasi #${id}: ${err.message || 'Kesalahan tidak diketahui'}`) });
                }
                return response.json();
            })
        );
        
        Promise.all(promises)
            .then(() => {
                showAlert(`Berhasil mengirim ${ids.length} notifikasi`);
                fetchNotifications();
            })
            .catch(error => {
                console.error('Error sending notifications:', error);
                showAlert(error.message || 'Gagal mengirim beberapa notifikasi', 'error');
            })
            .finally(() => {
                document.getElementById('bulk-send-btn').innerHTML = originalBtnText;
                document.getElementById('bulk-send-btn').disabled = false;
            });
    }
    
    function bulkDelete(ids) {
        const originalBtnText = document.getElementById('bulk-delete-btn').innerHTML;
        document.getElementById('bulk-delete-btn').innerHTML = `<svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menghapus...`;
        document.getElementById('bulk-delete-btn').disabled = true;
        
        const promises = ids.map(id => 
            fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: { 
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(`Gagal menghapus notifikasi #${id}: ${err.message || 'Kesalahan tidak diketahui'}`) });
                }
                return response.json();
            })
        );
        
        Promise.all(promises)
            .then(() => {
                showAlert(`Berhasil menghapus ${ids.length} notifikasi`);
                fetchNotifications();
            })
            .catch(error => {
                console.error('Error deleting notifications:', error);
                showAlert(error.message || 'Gagal menghapus beberapa notifikasi', 'error');
            })
            .finally(() => {
                document.getElementById('bulk-delete-btn').innerHTML = originalBtnText;
                document.getElementById('bulk-delete-btn').disabled = false;
            });
    }

    // Initial fetch
    fetchNotifications();
});
</script>
@endpush 