@extends('admin.layouts.app')

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
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Notifikasi (<span id="notificationCount">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola notifikasi push untuk user.</p>
    </div>

    <div id="notification-alert" class="hidden"></div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="flex items-center gap-2">
            <div class="relative w-full md:w-64">
                <input id="search-input" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Cari notifikasi...">
                <div class="absolute left-3 top-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex rounded-md shadow-sm" role="group">
              <button type="button" data-status="" class="status-filter-btn px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-orange-500 dark:focus:text-white ring-2 ring-orange-500">
                All
              </button>
              <button type="button" data-status="sent" class="status-filter-btn px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-orange-500 dark:focus:text-white">
                Sent
              </button>
              <button type="button" data-status="scheduled" class="status-filter-btn px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-md hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-orange-500 dark:focus:text-white">
                Scheduled
              </button>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <button id="process-due-btn" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Proses Jadwal
            </button>
            <a href="/admin/notifications/create" class="bg-indigo-800 text-white px-4 py-2 rounded-md text-sm flex items-center">
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
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">Pesan</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">Tanggal Terjadwal</th>
                        <th scope="col" class="relative px-3 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody id="notifications-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Loading state -->
                    <tr><td colspan="5" class="px-3 py-4 text-center"><div class="animate-pulse flex justify-center items-center"><svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="ml-2">Loading notifications...</span></div></td></tr>
                </tbody>
            </table>
        </div>
        <div id="pagination-controls" class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6"></div>
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
    const processDueBtn = document.getElementById('process-due-btn');

    // Check for success message in URL
    const urlParams = new URLSearchParams(window.location.search);
    const successMessage = urlParams.get('success');
    if (successMessage) {
        showAlert(decodeURIComponent(successMessage));
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
            tableBody.innerHTML = `<tr><td colspan="5" class="px-3 py-4 text-center text-sm text-gray-500">Tidak ada data notifikasi</td></tr>`;
            return;
        }

        notifications.forEach(notification => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';

            const statusBadge = notification.is_sent 
                ? `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-sent">Sent</span>`
                : `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-scheduled">Scheduled</span>`;
            
            const title = notification.title || 'No Title';
            const message = notification.message || 'No Message';
            
            row.innerHTML = `
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
                <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-40">
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                <a href="/admin/notifications/${notification.id}/edit" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem"><i class="bi bi-pencil mr-2"></i> Edit</a>
                                ${!notification.is_sent ? `<button onclick="sendNow(${notification.id})" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem"><i class="bi bi-send mr-2"></i> Send Now</button>` : ''}
                                <button onclick="deleteNotification(${notification.id})" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem"><i class="bi bi-trash mr-2"></i> Delete</button>
                            </div>
                        </div>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    function renderPagination(paginationData) {
        paginationControls.innerHTML = '';
        
        if (!paginationData || !paginationData.total || paginationData.total <= 0) {
            return;
        }
        
        if (paginationData.total <= paginationData.per_page) {
            paginationControls.innerHTML = `
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div><p class="text-sm text-gray-700 dark:text-gray-400">Showing <span class="font-medium">${paginationData.total}</span> results</p></div>
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
                <button data-page="${paginationData.current_page - 1}" ${!paginationData.prev_page_url ? 'disabled' : ''} class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 ${!paginationData.prev_page_url ? 'opacity-50 cursor-not-allowed' : ''}">Previous</button>
                <button data-page="${paginationData.current_page + 1}" ${!paginationData.next_page_url ? 'disabled' : ''} class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 ${!paginationData.next_page_url ? 'opacity-50 cursor-not-allowed' : ''}">Next</button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div><p class="text-sm text-gray-700 dark:text-gray-400">Showing <span class="font-medium">${paginationData.from || 0}</span> to <span class="font-medium">${paginationData.to || 0}</span> of <span class="font-medium">${paginationData.total}</span> results</p></div>
                <div><nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">${links}</nav></div>
            </div>
        `;
    }
    
    function showLoadingState() {
        tableBody.innerHTML = `<tr><td colspan="5" class="px-3 py-4 text-center"><div class="animate-pulse flex justify-center items-center"><svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="ml-2">Loading notifications...</span></div></td></tr>`;
    }

    function showErrorState(message = 'Error loading notifications. Please try again later.') {
        tableBody.innerHTML = `<tr><td colspan="5" class="px-3 py-4 text-center text-sm text-red-500">${message}</td></tr>`;
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
        const alertEl = document.getElementById('notification-alert');
        const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        alertEl.className = `${bgColor} border px-4 py-3 rounded relative mb-4`;
        alertEl.innerHTML = `<span class="block sm:inline">${message}</span><button type="button" onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">&times;</button>`;
        alertEl.style.display = 'block';
        setTimeout(() => {
            if (alertEl.style.display !== 'none') {
                alertEl.style.display = 'none';
            }
        }, 5000);
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
    
    // Process due notifications
    processDueBtn.addEventListener('click', function() {
        const originalText = this.innerHTML;
        this.innerHTML = `<svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...`;
        this.disabled = true;
        
        fetch(`${API_URL}/process-due`, {
            method: 'POST',
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
                return response.json().then(err => { throw new Error(err.message || 'Failed to process notifications') });
            }
            return response.json();
        })
        .then(data => {
            showAlert(data.message || `Processed ${data.data?.count || 0} notifications successfully`);
            fetchNotifications();
        })
        .catch(error => {
            console.error('Error processing notifications:', error);
            showAlert(error.message || 'Failed to process notifications', 'error');
        })
        .finally(() => {
            this.innerHTML = originalText;
            this.disabled = false;
        });
    });

    // Global functions for actions
    window.deleteNotification = function(id) {
        if (!confirm('Are you sure you want to delete this notification?')) return;

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
                 return response.json().then(err => { throw new Error(err.message || 'Failed to delete notification') });
            }
            return response.json();
        })
        .then(data => {
            showAlert(data.message || 'Notification deleted successfully.');
            fetchNotifications();
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
            showAlert(error.message || 'Failed to delete notification.', 'error');
        });
    };
    
    window.sendNow = function(id) {
        if (!confirm('Are you sure you want to send this notification now?')) return;

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
                 return response.json().then(err => { throw new Error(err.message || 'Failed to send notification') });
            }
            return response.json();
        })
        .then(data => {
            showAlert(data.message || 'Notification sent successfully.');
            fetchNotifications();
        })
        .catch(error => {
            console.error('Error sending notification:', error);
            showAlert(error.message || 'Failed to send notification.', 'error');
        });
    };

    // Initial fetch
    fetchNotifications();
});
</script>
@endpush 